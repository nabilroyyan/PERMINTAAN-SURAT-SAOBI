<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PendudukController extends Controller
{
    public function index(Request $request)
    {
        $query = Penduduk::with('user');

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_lengkap', 'like', "%{$keyword}%")
                    ->orWhere('nik', 'like', "%{$keyword}%")
                    ->orWhere('no_kk', 'like', "%{$keyword}%")
                    ->orWhere('dusun', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status_akun')) {
            if ($request->status_akun === 'terdaftar') {
                $query->whereNotNull('user_id');
            } elseif ($request->status_akun === 'belum') {
                $query->whereNull('user_id');
            }
        }

        $penduduks = $query->latest()->paginate(15)->withQueryString();
        $totalPenduduk = Penduduk::count();
        $totalAkun = Penduduk::whereNotNull('user_id')->count();

        return view('admin.penduduk.index', compact('penduduks', 'totalPenduduk', 'totalAkun'));
    }

    public function create()
    {
        return view('admin.penduduk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => ['required', 'digits:16', 'unique:penduduks,nik'],
            'no_kk' => ['required', 'digits:16'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'dusun' => ['nullable', 'string', 'max:100'],
            'rt' => ['nullable', 'string', 'max:5'],
            'rw' => ['nullable', 'string', 'max:5'],
            'agama' => ['nullable', 'string', 'max:30'],
            'pekerjaan' => ['nullable', 'string', 'max:100'],
            'status_perkawinan' => ['nullable', 'string', 'max:50'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['nullable', 'string', 'in:warga,admin,kepala_desa'],
        ]);

        $userId = null;
        if (!empty($validated['email'])) {
            $plain = $validated['password'] ?? '12345678';
            $role = $validated['role'] ?? 'warga';
            $user = User::create([
                'name' => $validated['nama_lengkap'],
                'nik' => $validated['nik'],
                'email' => strtolower($validated['email']),
                'password' => Hash::make($plain),
                'password_plain' => $plain,
                'role' => $role,
            ]);
            $userId = $user->id;
        }

        $pendudukData = collect($validated)->except(['email', 'password', 'role'])->toArray();
        $pendudukData['user_id'] = $userId;

        Penduduk::create($pendudukData);

        return redirect()->route('admin.penduduk.index')
            ->with('success', 'Data penduduk ' . ($userId ? 'beserta akun login ' : '') . 'berhasil ditambahkan.');
    }

    public function import(Request $request)
    {
        // Tingkatkan batas waktu eksekusi & memori untuk pemrosesan file data besar (sampai 5000+ penduduk)
        @ini_set('max_execution_time', 600);
        @set_time_limit(600);
        @ini_set('memory_limit', '1024M');
        \Illuminate\Support\Facades\DB::disableQueryLog();

        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:20480'],
        ], [
            'file_excel.required' => 'Pilih file Excel terlebih dahulu.',
            'file_excel.mimes' => 'Format file harus berupa .xlsx, .xls, atau .csv.',
            'file_excel.max' => 'Ukuran file maksimal adalah 20 MB.',
        ]);

        $startTime = microtime(true);

        try {
            $file = $request->file('file_excel');
            
            // Baca data hanya teks/nilai murni untuk efisiensi memori & kecepatan tinggi
            $reader = IOFactory::createReaderForFile($file->getRealPath());
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            $importedCount = 0;
            $updatedCount = 0;
            $accountCount = 0;

            // Cache hash password agar Bcrypt tidak dieksekusi berulang untuk kata sandi yang sama
            $passwordHashCache = [];

            // Filter baris data yang valid (skip header & baris kosong)
            $validRows = [];
            foreach ($rows as $index => $row) {
                if ($index === 1) {
                    continue; // Lewati header
                }

                $nik = trim((string) ($row['A'] ?? ''));
                $nama = trim((string) ($row['C'] ?? ''));

                if (!empty($nik) && !empty($nama)) {
                    $validRows[] = $row;
                }
            }

            if (empty($validRows)) {
                return back()->with('error', 'Tidak ditemukan data penduduk yang valid di dalam file Excel.');
            }

            // Proses batch secara bertahap (chunk 250 baris) dengan bulk pre-fetching
            $chunks = array_chunk($validRows, 250);

            \Illuminate\Support\Facades\DB::beginTransaction();

            foreach ($chunks as $chunk) {
                $chunkNiks = [];
                $chunkEmails = [];

                foreach ($chunk as $r) {
                    $nik = trim((string) ($r['A'] ?? ''));
                    $email = strtolower(trim((string) ($r['O'] ?? '')));
                    if ($nik) $chunkNiks[] = $nik;
                    if ($email) $chunkEmails[] = $email;
                }

                // Ambil data yang sudah ada di database sekaligus dalam 1 query batch
                $existingPenduduks = Penduduk::whereIn('nik', $chunkNiks)->get()->keyBy('nik');
                $existingUsers = User::whereIn('nik', $chunkNiks)
                    ->orWhereIn('email', $chunkEmails)
                    ->get();

                $usersByNik = [];
                $usersByEmail = [];
                foreach ($existingUsers as $u) {
                    if ($u->nik) $usersByNik[$u->nik] = $u;
                    if ($u->email) $usersByEmail[$u->email] = $u;
                }

                foreach ($chunk as $row) {
                    $nik = trim((string) ($row['A'] ?? ''));
                    $noKk = trim((string) ($row['B'] ?? ''));
                    $nama = trim((string) ($row['C'] ?? ''));

                    // Normalisasi tanggal lahir
                    $tanggalLahirRaw = $row['E'] ?? null;
                    $tanggalLahir = null;
                    if (!empty($tanggalLahirRaw)) {
                        try {
                            if (is_numeric($tanggalLahirRaw)) {
                                $tanggalLahir = Carbon::instance(
                                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalLahirRaw)
                                )->format('Y-m-d');
                            } else {
                                $tanggalLahir = Carbon::parse($tanggalLahirRaw)->format('Y-m-d');
                            }
                        } catch (\Exception $e) {
                            $tanggalLahir = null;
                        }
                    }

                    // Normalisasi jenis kelamin
                    $jkRaw = strtoupper(trim((string) ($row['F'] ?? 'L')));
                    $jenisKelamin = (str_starts_with($jkRaw, 'P') || $jkRaw === 'PEREMPUAN') ? 'Perempuan' : 'Laki-laki';

                    // Normalisasi role pengguna (dari Kolom Q)
                    $roleRaw = strtolower(trim((string) ($row['Q'] ?? '')));
                    if (str_contains($roleRaw, 'admin')) {
                        $roleVal = UserRole::ADMIN->value;
                    } elseif (str_contains($roleRaw, 'kades') || str_contains($roleRaw, 'kepala')) {
                        $roleVal = UserRole::KEPALA_DESA->value;
                    } else {
                        $roleVal = UserRole::WARGA->value;
                    }

                    $penduduk = $existingPenduduks->get($nik);
                    $userId = $penduduk?->user_id;

                    // Email dan Password dari Excel
                    $email = strtolower(trim((string) ($row['O'] ?? '')));
                    $passwordRaw = trim((string) ($row['P'] ?? ''));

                    if (!empty($email)) {
                        $user = $usersByNik[$nik] ?? ($usersByEmail[$email] ?? null);

                        $plainPassword = $passwordRaw ?: 'saobi123';
                        if (!isset($passwordHashCache[$plainPassword])) {
                            $passwordHashCache[$plainPassword] = Hash::make($plainPassword);
                        }
                        $hashedPassword = $passwordHashCache[$plainPassword];

                        if ($user) {
                            $updateData = [
                                'name' => $nama,
                                'nik' => $nik,
                                'email' => $email,
                            ];
                            // Hanya timpa role jika kolom Q diisi di Excel
                            if (!empty($roleRaw)) {
                                $updateData['role'] = $roleVal;
                            }
                            if (!empty($passwordRaw)) {
                                $updateData['password'] = $hashedPassword;
                                $updateData['password_plain'] = $passwordRaw;
                            }
                            $user->update($updateData);
                            $userId = $user->id;
                        } else {
                            $newUser = User::create([
                                'name' => $nama,
                                'nik' => $nik,
                                'email' => $email,
                                'password' => $hashedPassword,
                                'password_plain' => $plainPassword,
                                'role' => $roleVal,
                            ]);
                            $userId = $newUser->id;
                            $usersByNik[$nik] = $newUser;
                            $usersByEmail[$email] = $newUser;
                        }
                        $accountCount++;
                    }

                    $data = [
                        'no_kk' => $noKk ?: ($penduduk->no_kk ?? $nik),
                        'nama_lengkap' => $nama,
                        'tempat_lahir' => trim((string) ($row['D'] ?? '')) ?: null,
                        'tanggal_lahir' => $tanggalLahir,
                        'jenis_kelamin' => $jenisKelamin,
                        'alamat' => trim((string) ($row['G'] ?? '')) ?: null,
                        'dusun' => trim((string) ($row['H'] ?? '')) ?: null,
                        'rt' => trim((string) ($row['I'] ?? '')) ?: null,
                        'rw' => trim((string) ($row['J'] ?? '')) ?: null,
                        'agama' => trim((string) ($row['K'] ?? '')) ?: 'Islam',
                        'pekerjaan' => trim((string) ($row['L'] ?? '')) ?: null,
                        'status_perkawinan' => trim((string) ($row['M'] ?? '')) ?: null,
                        'telepon' => trim((string) ($row['N'] ?? '')) ?: null,
                        'user_id' => $userId,
                    ];

                    if ($penduduk) {
                        $penduduk->update($data);
                        $updatedCount++;
                    } else {
                        $data['nik'] = $nik;
                        $newPenduduk = Penduduk::create($data);
                        $existingPenduduks->put($nik, $newPenduduk);
                        $importedCount++;
                    }
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            $elapsedSeconds = round(microtime(true) - $startTime, 2);

            return redirect()->route('admin.penduduk.index')
                ->with('success', "Proses import selesai dalam {$elapsedSeconds} detik! Berhasil menambahkan {$importedCount} data baru, memperbarui {$updatedCount} warga, dan memproses {$accountCount} akun login.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }

    public function downloadTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Master Penduduk Saobi');

        // Headers (17 Kolom lengkap termasuk Role Akun)
        $headers = [
            'A1' => 'NIK (16 Digit)',
            'B1' => 'No KK (16 Digit)',
            'C1' => 'Nama Lengkap',
            'D1' => 'Tempat Lahir',
            'E1' => 'Tanggal Lahir (YYYY-MM-DD)',
            'F1' => 'Jenis Kelamin (Laki-laki / Perempuan)',
            'G1' => 'Alamat Lengkap',
            'H1' => 'Dusun',
            'I1' => 'RT',
            'J1' => 'RW',
            'K1' => 'Agama',
            'L1' => 'Pekerjaan',
            'M1' => 'Status Perkawinan',
            'N1' => 'Nomor HP / WhatsApp',
            'O1' => 'Email Akun Login (Warga)',
            'P1' => 'Password Akun Login (Default: saobi123)',
            'Q1' => 'Role Akun (warga / admin / kepala_desa)',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Style Header Modern
        $sheet->getStyle('A1:Q1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF047857'], // Emerald-700
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Contoh Data Realistis (Warga, Admin Desa, Kepala Desa)
        $sampleData = [
            [
                '3529061204950001',
                '3529060101200001',
                'Ahmad Warga Saobi',
                'Sumenep',
                '1995-04-12',
                'Laki-laki',
                'Dusun Saobi Timur RT 01 / RW 01, Desa Saobi, Kec. Kangayan, Kab. Sumenep',
                'Saobi Timur',
                '01',
                '01',
                'Islam',
                'Nelayan',
                'Kawin',
                '081234567890',
                'ahmad@saobi.desa.id',
                'saobi123',
                'warga',
            ],
            [
                '3529065008980002',
                '3529060101200001',
                'Siti Aminah',
                'Sumenep',
                '1998-08-10',
                'Perempuan',
                'Dusun Saobi Timur RT 01 / RW 01, Desa Saobi, Kec. Kangayan, Kab. Sumenep',
                'Saobi Timur',
                '01',
                '01',
                'Islam',
                'Mengurus Rumah Tangga',
                'Kawin',
                '081234567891',
                'siti@saobi.desa.id',
                'saobi123',
                'warga',
            ],
            [
                '3529000000000001',
                '3529060101200002',
                'Bahrul Anam (Admin)',
                'Sumenep',
                '1990-01-01',
                'Laki-laki',
                'Dusun Saobi Barat RT 02 / RW 01, Desa Saobi, Kec. Kangayan, Kab. Sumenep',
                'Saobi Barat',
                '02',
                '01',
                'Islam',
                'Perangkat Desa',
                'Kawin',
                '081200000001',
                'admin@desasaobi.id',
                'password',
                'admin',
            ],
            [
                '3529000000000002',
                '3529060101200003',
                'H. Mohamad Hasan (Kepala Desa)',
                'Sumenep',
                '1980-05-15',
                'Laki-laki',
                'Dusun Saobi Tengah RT 01 / RW 02, Desa Saobi, Kec. Kangayan, Kab. Sumenep',
                'Saobi Tengah',
                '01',
                '02',
                'Islam',
                'Kepala Desa',
                'Kawin',
                '081200000002',
                'kades@desasaobi.id',
                'password',
                'kepala_desa',
            ],
        ];

        $rowNum = 2;
        foreach ($sampleData as $row) {
            $colLetter = 'A';
            foreach ($row as $val) {
                if (in_array($colLetter, ['A', 'B', 'I', 'J', 'N'])) {
                    $sheet->setCellValueExplicit($colLetter . $rowNum, $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                } else {
                    $sheet->setCellValue($colLetter . $rowNum, $val);
                }
                $colLetter++;
            }
            $rowNum++;
        }

        // Set column widths manual
        $widths = [
            'A' => 22, 'B' => 22, 'C' => 28, 'D' => 18, 'E' => 16,
            'F' => 16, 'G' => 45, 'H' => 18, 'I' => 8, 'J' => 8,
            'K' => 14, 'L' => 22, 'M' => 18, 'N' => 20, 'O' => 30,
            'P' => 22, 'Q' => 22,
        ];
        foreach ($widths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });

        $fileName = 'template_master_data_penduduk_desa_saobi.xlsx';
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    /**
     * Download dataset 5.000 data penduduk simulasi resmi Desa Saobi
     */
    public function downloadSample5000()
    {
        $path = public_path('data_penduduk_desa_saobi_5000_warga.xlsx');
        if (!file_exists($path)) {
            $path = base_path('data_penduduk_desa_saobi_5000_warga.xlsx');
        }

        if (!file_exists($path)) {
            return back()->with('error', 'File data 5.000 penduduk belum tersedia di server.');
        }

        return response()->download($path, 'data_penduduk_desa_saobi_5000_warga.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Mengatur akun login & hak akses / role penduduk (Warga, Admin, Kepala Desa)
     */
    public function updateAccount(Request $request, Penduduk $penduduk)
    {
        $user = $penduduk->user ?? User::where('nik', $penduduk->nik)->first();

        $validated = $request->validate([
            'role' => ['required', Rule::enum(UserRole::class)],
            'email' => [
                'required',
                'email',
                'max:255',
                $user ? Rule::unique('users', 'email')->ignore($user->id) : Rule::unique('users', 'email'),
            ],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'role.required' => 'Pilih role akun yang valid.',
            'email.required' => 'Alamat email akun wajib diisi.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
        ]);

        if ($user) {
            $user->role = $validated['role'];
            $user->email = strtolower($validated['email']);
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
                $user->password_plain = $validated['password'];
            }
            $user->save();

            if (!$penduduk->user_id) {
                $penduduk->update(['user_id' => $user->id]);
            }
        } else {
            $plain = !empty($validated['password']) ? $validated['password'] : 'saobi123';
            $user = User::create([
                'name' => $penduduk->nama_lengkap,
                'nik' => $penduduk->nik,
                'email' => strtolower($validated['email']),
                'role' => $validated['role'],
                'password' => Hash::make($plain),
                'password_plain' => $plain,
            ]);

            $penduduk->update(['user_id' => $user->id]);
        }

        $roleObj = UserRole::tryFrom($validated['role']);
        $roleLabel = $roleObj ? $roleObj->label() : ucfirst($validated['role']);

        return back()->with('success', 'Akun & Role untuk ' . $penduduk->nama_lengkap . ' berhasil diatur sebagai ' . $roleLabel . '.');
    }

    public function destroy(Penduduk $penduduk)
    {
        $penduduk->delete();
        return back()->with('success', 'Data penduduk berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        ]);

        $userId = null;
        if (!empty($validated['email'])) {
            $user = User::create([
                'name' => $validated['nama_lengkap'],
                'nik' => $validated['nik'],
                'email' => strtolower($validated['email']),
                'password' => Hash::make($validated['password'] ?? '12345678'),
                'role' => 'client',
            ]);
            $userId = $user->id;
        }

        $pendudukData = collect($validated)->except(['email', 'password'])->toArray();
        $pendudukData['user_id'] = $userId;

        Penduduk::create($pendudukData);

        return redirect()->route('admin.penduduk.index')
            ->with('success', 'Data penduduk ' . ($userId ? 'beserta akun login ' : '') . 'berhasil ditambahkan.');
    }

    public function import(Request $request)
    {
        // Tingkatkan batas waktu eksekusi & memori untuk pemrosesan file data besar
        @ini_set('max_execution_time', 300);
        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ], [
            'file_excel.required' => 'Pilih file Excel terlebih dahulu.',
            'file_excel.mimes' => 'Format file harus berupa .xlsx, .xls, atau .csv.',
            'file_excel.max' => 'Ukuran file maksimal adalah 10 MB.',
        ]);

        try {
            $file = $request->file('file_excel');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            $importedCount = 0;
            $updatedCount = 0;
            $accountCount = 0;

            // Cache hash password agar tidak membebani CPU (Bcrypt memakan ~0.3 detik per hash)
            $passwordHashCache = [];

            \Illuminate\Support\Facades\DB::beginTransaction();

            foreach ($rows as $index => $row) {
                if ($index == 1) {
                    continue; // Lewati header baris 1
                }

                $nik = trim((string) ($row['A'] ?? ''));
                $noKk = trim((string) ($row['B'] ?? ''));
                $nama = trim((string) ($row['C'] ?? ''));

                if (empty($nik) || empty($nama)) {
                    continue; // Lewati baris kosong
                }

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

                $penduduk = Penduduk::where('nik', $nik)->first();
                $userId = $penduduk?->user_id;

                // Email dan Password dari Excel
                $email = strtolower(trim((string) ($row['O'] ?? '')));
                $passwordRaw = trim((string) ($row['P'] ?? ''));

                if (!empty($email)) {
                    // Cari apakah user dengan email atau NIK ini sudah ada
                    $user = User::where('email', $email)->orWhere('nik', $nik)->first();

                    $plainPassword = $passwordRaw ?: '12345678';
                    if (!isset($passwordHashCache[$plainPassword])) {
                        $passwordHashCache[$plainPassword] = Hash::make($plainPassword);
                    }
                    $hashedPassword = $passwordHashCache[$plainPassword];

                    if ($user) {
                        $updateData = [
                            'name' => $nama,
                            'nik' => $nik,
                            'email' => $email,
                            'role' => 'client',
                        ];
                        if (!empty($passwordRaw)) {
                            $updateData['password'] = $hashedPassword;
                        }
                        $user->update($updateData);
                        $userId = $user->id;
                    } else {
                        $newUser = User::create([
                            'name' => $nama,
                            'nik' => $nik,
                            'email' => $email,
                            'password' => $hashedPassword,
                            'role' => 'client',
                        ]);
                        $userId = $newUser->id;
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
                    Penduduk::create($data);
                    $importedCount++;
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('admin.penduduk.index')
                ->with('success', "Proses import selesai! Berhasil menambahkan {$importedCount} data baru, memperbarui {$updatedCount} warga, dan mengaktifkan {$accountCount} akun login.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }

    public function downloadTemplate(): StreamedResponse
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Penduduk Saobi');

        // Headers
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
            'P1' => 'Password Akun Login (Default: 12345678)',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Style Header
        $sheet->getStyle('A1:P1')->getFont()->setBold(true);

        // Contoh Data Baris 2 & 3
        $sampleData = [
            [
                '3529012345670001',
                '3529012345670000',
                'Ahmad Warga Saobi',
                'Sumenep',
                '1995-04-12',
                'Laki-laki',
                'Dusun Saobi Timur RT 01 / RW 02',
                'Saobi Timur',
                '01',
                '02',
                'Islam',
                'Nelayan',
                'Kawin',
                '081234567890',
                'ahmad@saobi.desa.id',
                'password',
            ],
            [
                '3529012345670002',
                '3529012345670000',
                'Siti Aminah',
                'Sumenep',
                '1998-08-20',
                'Perempuan',
                'Dusun Saobi Timur RT 01 / RW 02',
                'Saobi Timur',
                '01',
                '02',
                'Islam',
                'Mengurus Rumah Tangga',
                'Kawin',
                '081234567891',
                'siti@saobi.desa.id',
                'password',
            ],
        ];

        $rowNum = 2;
        foreach ($sampleData as $row) {
            $colLetter = 'A';
            foreach ($row as $val) {
                if (in_array($colLetter, ['A', 'B'])) {
                    $sheet->setCellValueExplicit($colLetter . $rowNum, $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                } else {
                    $sheet->setCellValue($colLetter . $rowNum, $val);
                }
                $colLetter++;
            }
            $rowNum++;
        }

        // Auto width columns A - P
        foreach (range('A', 'P') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
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

    public function destroy(Penduduk $penduduk)
    {
        $penduduk->delete();
        return back()->with('success', 'Data penduduk berhasil dihapus.');
    }
}

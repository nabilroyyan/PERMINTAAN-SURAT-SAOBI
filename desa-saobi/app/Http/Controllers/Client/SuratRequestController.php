<?php

namespace App\Http\Controllers\Client;

use App\Enums\FormFieldType;
use App\Http\Controllers\Controller;
use App\Models\SuratDocument;
use App\Models\SuratRequest;
use App\Models\SuratType;
use App\Support\FormFieldSchema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SuratRequestController extends Controller
{
    public function index(Request $request)
    {
        $surat = SuratRequest::with('suratType')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('client.surat.index', compact('surat'));
    }

    public function create(Request $request)
    {
        $jenisSurat = SuratType::where('is_active', true)
            ->with('template')
            ->orderBy('nama')
            ->get();

        // Peta field isian dinamis (gabungan template + jenis surat) per jenis surat
        $fieldMap = $jenisSurat->mapWithKeys(function ($jenis) {
            return [$jenis->id => $jenis->effectiveFormFields()];
        });

        // Peta persyaratan dokumen per jenis surat (nama + wajib/opsional)
        $syaratMap = $jenisSurat->mapWithKeys(function ($jenis) {
            return [$jenis->id => $jenis->persyaratanItems()];
        });

        // Nilai siap isi otomatis dari data penduduk pemohon
        $penduduk = $request->user()->penduduk;
        $pendudukDefaults = [
            'nama' => $penduduk?->nama_lengkap ?? $request->user()->name,
            'nik' => $request->user()->nik,
            'tempat_lahir' => $penduduk?->tempat_lahir,
            'tanggal_lahir' => $penduduk?->tanggal_lahir?->format('Y-m-d'),
            'jenis_kelamin' => $penduduk?->jenis_kelamin,
            'agama' => $penduduk?->agama,
            'pekerjaan' => $penduduk?->pekerjaan,
            'status_perkawinan' => $penduduk?->status_perkawinan,
            'dusun' => $penduduk?->dusun,
            'rt' => $penduduk?->rt,
            'rw' => $penduduk?->rw,
            'alamat' => $penduduk?->alamat,
            'telepon' => $penduduk?->telepon,
        ];

        return view('client.surat.create', compact('jenisSurat', 'fieldMap', 'syaratMap', 'pendudukDefaults'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'surat_type_id' => ['required', 'exists:surat_types,id'],
        ]);

        $jenisSurat = SuratType::where('is_active', true)
            ->with('template')
            ->findOrFail($validated['surat_type_id']);

        $fields = $jenisSurat->effectiveFormFields();

        [$rules, $attributes] = FormFieldSchema::validationRules($fields);
        if (!empty($rules)) {
            $request->validate($rules, [], $attributes);
        }

        $tambahan = [];
        $fileInputs = [];

        foreach ($fields as $field) {
            $kode = $field['kode'];
            $tipe = $field['tipe'] ?? FormFieldType::TEXT->value;

            if ($tipe === FormFieldType::FILE->value) {
                if ($request->hasFile("tambahan.{$kode}")) {
                    $fileInputs[$kode] = $request->file("tambahan.{$kode}");
                }
                $tambahan[$kode] = null;
                continue;
            }

            if ($tipe === FormFieldType::CHECKBOX->value) {
                $tambahan[$kode] = array_values((array) $request->input("tambahan.{$kode}", []));
                continue;
            }

            $tambahan[$kode] = $request->input("tambahan.{$kode}");
        }

        // Persyaratan dokumen (unggah berkas). Berkas wajib bila syarat ditandai Wajib.
        $syaratDefs = $jenisSurat->persyaratanItems();
        $syaratRules = [];
        foreach ($syaratDefs as $i => $item) {
            $syaratRules["syarat.{$i}"] = [
                ($item['wajib'] ?? false) ? 'required' : 'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ];
        }
        if (!empty($syaratRules)) {
            $request->validate($syaratRules, [], []);
        }

        $syaratFiles = [];
        foreach ($syaratDefs as $i => $item) {
            if ($request->hasFile("syarat.{$i}")) {
                $syaratFiles[$i] = $request->file("syarat.{$i}");
            }
        }

        $permohonan = DB::transaction(function () use ($request, $jenisSurat, $fields, $tambahan, $fileInputs, $syaratDefs, $syaratFiles) {
            $penduduk = $request->user()->penduduk;

            $data = [
                'nama' => $penduduk?->nama_lengkap ?? $request->user()->name,
                'nik' => $request->user()->nik,
                'tempat_lahir' => $penduduk?->tempat_lahir,
                'tanggal_lahir' => $penduduk?->tanggal_lahir?->format('Y-m-d'),
                'jenis_kelamin' => $penduduk?->jenis_kelamin,
                'status_perkawinan' => $penduduk?->status_perkawinan,
                'agama' => $penduduk?->agama ?? 'Islam',
                'pekerjaan' => $penduduk?->pekerjaan,
                'dusun' => $penduduk?->dusun,
                'rt' => $penduduk?->rt,
                'rw' => $penduduk?->rw,
            ];

            // Nilai field dinamis disimpan datar agar dapat dipakai variabel template
            foreach ($tambahan as $kode => $nilai) {
                if (is_scalar($nilai) && $nilai !== null && $nilai !== '') {
                    $data[$kode] = $nilai;
                }
            }

            $data['alamat'] = $data['alamat'] ?? ($penduduk?->alamat ?? '');
            $data['keperluan'] = $data['keperluan'] ?? '';
            $data['tambahan'] = $tambahan;

            $surat = SuratRequest::create([
                'user_id' => $request->user()->id,
                'surat_type_id' => $jenisSurat->id,
                'data_pemohon' => $data,
                'status' => 'pending',
            ]);

            $surat->statusHistories()->create([
                'user_id' => $request->user()->id,
                'status' => 'pending',
                'catatan' => 'Permohonan surat berhasil diajukan.',
            ]);

            $definisi = collect($fields)->keyBy('kode');

            foreach ($fileInputs as $kode => $file) {
                $path = $file->store('surat-dokumen', 'public');

                $surat->documents()->create([
                    'nama_file' => $definisi[$kode]['label'] ?? $file->getClientOriginalName(),
                    'path_file' => $path,
                    'jenis_dokumen' => $kode,
                ]);
            }

            // Simpan berkas persyaratan yang diunggah warga (opsional)
            foreach ($syaratFiles as $i => $file) {
                $path = $file->store('surat-dokumen', 'public');

                $surat->documents()->create([
                    'nama_file' => $syaratDefs[$i]['nama'] ?? $file->getClientOriginalName(),
                    'path_file' => $path,
                    'jenis_dokumen' => 'syarat',
                ]);
            }

            return $surat;
        });

        return redirect()
            ->route('client.surat.show', $permohonan)
            ->with('success', 'Permohonan surat berhasil dikirim.');
    }

    public function show(Request $request, SuratRequest $suratRequest)
    {
        abort_unless(
            $suratRequest->user_id === $request->user()->id,
            404
        );

        $suratRequest->load('suratType.template', 'statusHistories', 'documents');

        return view('client.surat.show', compact('suratRequest'));
    }

    public function downloadDokumen(Request $request, SuratRequest $suratRequest, SuratDocument $document)
    {
        abort_unless($suratRequest->user_id === $request->user()->id, 403);
        abort_unless($document->surat_request_id === $suratRequest->id, 404);

        if (!Storage::disk('public')->exists($document->path_file)) {
            abort(404, 'Berkas tidak ditemukan di server.');
        }

        return Storage::disk('public')->download($document->path_file, $document->nama_file);
    }

    /**
     * Tampilkan berkas unggahan langsung di layar (inline) sebelum diunduh.
     */
    public function previewDokumen(Request $request, SuratRequest $suratRequest, SuratDocument $document)
    {
        abort_unless($suratRequest->user_id === $request->user()->id, 403);
        abort_unless($document->surat_request_id === $suratRequest->id, 404);

        if (!Storage::disk('public')->exists($document->path_file)) {
            abort(404, 'Berkas tidak ditemukan di server.');
        }

        $mime = Storage::disk('public')->mimeType($document->path_file) ?: 'application/octet-stream';

        return response()->file(Storage::disk('public')->path($document->path_file), [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $document->nama_file . '"',
        ]);
    }

    public function download(Request $request, SuratRequest $suratRequest)
    {
        abort_unless(
            $suratRequest->user_id === $request->user()->id,
            403,
            'Anda tidak memiliki akses ke surat ini.'
        );

        if ($suratRequest->status !== 'completed' || blank($suratRequest->file_surat)) {
            return back()->with('error', 'Surat belum selesai diterbitkan atau file tidak ditemukan.');
        }

        if (!Storage::disk('public')->exists($suratRequest->file_surat)) {
            return back()->with('error', 'File surat fisik tidak ditemukan di sistem.');
        }

        $namaFileDownload = str_replace(' ', '_', $suratRequest->suratType->nama ?? 'Surat') . '_' . ($suratRequest->nomor_surat ? str_replace(['/', '\\'], '-', $suratRequest->nomor_surat) : $suratRequest->id) . '.pdf';

        return Storage::disk('public')->download($suratRequest->file_surat, $namaFileDownload);
    }

    /**
     * Tampilkan berkas surat langsung di layar (inline) untuk dilihat sebelum diunduh.
     */
    public function preview(Request $request, SuratRequest $suratRequest)
    {
        abort_unless(
            $suratRequest->user_id === $request->user()->id,
            403,
            'Anda tidak memiliki akses ke surat ini.'
        );

        if ($suratRequest->status !== 'completed' || blank($suratRequest->file_surat)) {
            abort(404, 'Surat belum selesai diterbitkan atau file tidak ditemukan.');
        }

        if (!Storage::disk('public')->exists($suratRequest->file_surat)) {
            abort(404, 'File surat fisik tidak ditemukan di sistem.');
        }

        $nama = str_replace(' ', '_', $suratRequest->suratType->nama ?? 'Surat')
            . '_' . ($suratRequest->nomor_surat ? str_replace(['/', '\\'], '-', $suratRequest->nomor_surat) : $suratRequest->id) . '.pdf';

        return response()->file(Storage::disk('public')->path($suratRequest->file_surat), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $nama . '"',
        ]);
    }
}

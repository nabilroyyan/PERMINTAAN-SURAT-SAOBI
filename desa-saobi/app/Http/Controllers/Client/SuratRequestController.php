<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SuratRequest;
use App\Models\SuratType;
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

    public function create()
    {
        $jenisSurat = SuratType::where('is_active', true)
            ->with('template')
            ->orderBy('nama')
            ->get();

        // Peta variabel tambahan (yang harus diisi pemohon) per jenis surat
        $fieldMap = $jenisSurat->mapWithKeys(function ($jenis) {
            return [$jenis->id => ($jenis->template?->variabel_tambahan ?? [])];
        });

        return view('client.surat.create', compact('jenisSurat', 'fieldMap'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'surat_type_id' => ['required', 'exists:surat_types,id'],
            'alamat' => ['required', 'string', 'max:500'],
            'keperluan' => ['required', 'string', 'max:500'],
        ]);

        $jenisSurat = SuratType::where('is_active', true)
            ->with('template')
            ->findOrFail($validated['surat_type_id']);

        $fieldTambahan = $jenisSurat->template?->variabel_tambahan ?? [];

        if (!empty($fieldTambahan)) {
            $rules = [];
            $attributes = [];

            foreach ($fieldTambahan as $field) {
                $kode = $field['kode'];
                $rules["tambahan.{$kode}"] = [
                    ($field['wajib'] ?? false) ? 'required' : 'nullable',
                    ($field['tipe'] ?? 'text') === 'date' ? 'date' : 'string',
                    'max:2000',
                ];
                $attributes["tambahan.{$kode}"] = $field['label'] ?? $kode;
            }

            $request->validate($rules, [], $attributes);
        }

        $tambahan = [];
        foreach ($fieldTambahan as $field) {
            $tambahan[$field['kode']] = $request->input("tambahan.{$field['kode']}");
        }

        $permohonan = DB::transaction(function () use (
            $request,
            $validated,
            $jenisSurat,
            $tambahan
        ) {
            $penduduk = $request->user()->penduduk;
            $surat = SuratRequest::create([
                'user_id' => $request->user()->id,
                'surat_type_id' => $jenisSurat->id,
                'data_pemohon' => [
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
                    'alamat' => $validated['alamat'],
                    'keperluan' => $validated['keperluan'],
                    'tambahan' => $tambahan,
                ],
                'status' => 'pending',
            ]);

            $surat->statusHistories()->create([
                'user_id' => $request->user()->id,
                'status' => 'pending',
                'catatan' => 'Permohonan surat berhasil diajukan.',
            ]);

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

        $suratRequest->load('suratType.template', 'statusHistories');

        return view('client.surat.show', compact('suratRequest'));
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

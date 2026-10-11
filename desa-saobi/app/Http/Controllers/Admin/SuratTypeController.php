<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SuratTypeController extends Controller
{
    public function index()
    {
        $suratTypes = SuratType::withCount('suratRequests')->latest()->get();

        return view('admin.surat_type.index', compact('suratTypes'));
    }

    public function create()
    {
        return view('admin.surat_type.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kode' => ['required', 'string', 'max:20', 'unique:surat_types,kode'],
            'format_nomor' => ['required', 'string', 'max:255'],
            'nomor_terakhir' => ['required', 'integer', 'min:0'],
            'panjang_nomor' => ['required', 'integer', 'min:1', 'max:6'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'persyaratan_text' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $persyaratan = [];
        if (!empty($validated['persyaratan_text'])) {
            $lines = explode("\n", $validated['persyaratan_text']);
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (!empty($trimmed)) {
                    $persyaratan[] = $trimmed;
                }
            }
        }

        SuratType::create([
            'nama' => $validated['nama'],
            'kode' => strtoupper(trim($validated['kode'])),
            'format_nomor' => trim($validated['format_nomor']),
            'nomor_terakhir' => (int) $validated['nomor_terakhir'],
            'panjang_nomor' => (int) $validated['panjang_nomor'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'persyaratan' => $persyaratan,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.jenis-surat.index')
            ->with('success', 'Jenis surat dan format penomoran berhasil ditambahkan.');
    }

    public function edit(SuratType $jenis_surat)
    {
        return view('admin.surat_type.edit', ['suratType' => $jenis_surat]);
    }

    public function update(Request $request, SuratType $jenis_surat)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'kode' => ['required', 'string', 'max:20', Rule::unique('surat_types', 'kode')->ignore($jenis_surat->id)],
            'format_nomor' => ['required', 'string', 'max:255'],
            'nomor_terakhir' => ['required', 'integer', 'min:0'],
            'panjang_nomor' => ['required', 'integer', 'min:1', 'max:6'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'persyaratan_text' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $persyaratan = [];
        if (!empty($validated['persyaratan_text'])) {
            $lines = explode("\n", $validated['persyaratan_text']);
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (!empty($trimmed)) {
                    $persyaratan[] = $trimmed;
                }
            }
        }

        $jenis_surat->update([
            'nama' => $validated['nama'],
            'kode' => strtoupper(trim($validated['kode'])),
            'format_nomor' => trim($validated['format_nomor']),
            'nomor_terakhir' => (int) $validated['nomor_terakhir'],
            'panjang_nomor' => (int) $validated['panjang_nomor'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'persyaratan' => $persyaratan,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.jenis-surat.index')
            ->with('success', 'Format penomoran untuk ' . $jenis_surat->nama . ' berhasil diperbarui.');
    }

    public function destroy(SuratType $jenis_surat)
    {
        if ($jenis_surat->suratRequests()->exists()) {
            return back()->with('error', 'Jenis surat ini tidak dapat dihapus karena sudah memiliki riwayat permohonan surat warga.');
        }

        $jenis_surat->delete();
        return back()->with('success', 'Jenis surat berhasil dihapus.');
    }
}

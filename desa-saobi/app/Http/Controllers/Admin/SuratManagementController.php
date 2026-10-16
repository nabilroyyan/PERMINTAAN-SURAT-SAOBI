<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratRequest;
use App\Models\VillageSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SuratManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratRequest::with([
            'user',
            'suratType',
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $keyword = $request->q;

            $query->where(function ($q) use ($keyword) {
                $q->whereHas('user', function ($userQuery) use ($keyword) {
                    $userQuery->where('name', 'like', "%{$keyword}%")
                        ->orWhere('nik', 'like', "%{$keyword}%");
                })->orWhere('nomor_surat', 'like', "%{$keyword}%");
            });
        }

        $permohonan = $query->latest()->paginate(15)
            ->withQueryString();

        return view('admin.permohonan.index', compact('permohonan'));
    }

    public function show(SuratRequest $suratRequest)
    {
        $suratRequest->load(['user', 'suratType', 'statusHistories.user']);
        $villageSetting = VillageSetting::first();

        // Default nomor surat otomatis sesuai format jenis surat, namun tetap bisa diedit manual
        $nomorSuratDefault = $suratRequest->nomor_surat
            ?: ($suratRequest->suratType ? $suratRequest->suratType->generateNextNomorSurat() : '');

        return view('admin.permohonan.show', compact('suratRequest', 'villageSetting', 'nomorSuratDefault'));
    }

    public function updateStatus(
        Request $request,
        SuratRequest $suratRequest
    ) {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'revision',
                    'processing',
                    'approved',
                    'rejected',
                ]),
            ],
            'catatan_admin' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        if ($suratRequest->status === 'completed') {
            abort(403, 'Surat yang sudah selesai tidak dapat diubah statusnya melalui proses ini.');
        }

        if (
            $validated['status'] === 'rejected' &&
            blank($validated['catatan_admin'])
        ) {
            return back()->withErrors([
                'catatan_admin' => 'Alasan penolakan wajib diisi.',
            ]);
        }

        DB::transaction(function () use (
            $request,
            $suratRequest,
            $validated
        ) {
            $suratRequest->update([
                'status' => $validated['status'],
                'catatan_admin' => $validated['catatan_admin'] ?? null,
            ]);

            $suratRequest->statusHistories()->create([
                'user_id' => $request->user()->id,
                'status' => $validated['status'],
                'catatan' => $validated['catatan_admin'] ?? 'Status diubah ke ' . $validated['status'],
            ]);
        });

        return back()->with(
            'success',
            'Status permohonan berhasil diperbarui.'
        );
    }

    public function publish(Request $request, SuratRequest $suratRequest)
    {
        $validated = $request->validate([
            'nomor_surat' => [
                'required',
                'string',
                'max:100',
                Rule::unique('surat_requests', 'nomor_surat')->ignore($suratRequest->id),
            ],
            'tanggal_surat' => ['required', 'date'],
        ]);

        $villageSetting = VillageSetting::first() ?? new VillageSetting([
            'nama_desa' => 'Desa Saobi',
            'nama_kepala_desa' => 'HOSAINI, SH',
            'nip_kepala_desa' => '-',
            'alamat' => 'Jalan Raya Masjid No. 50. Email:desasaobi90@gmail.com Kode Pos 69491',
        ]);

        $suratRequest->load(['user.penduduk', 'suratType']);

        DB::transaction(function () use ($request, $suratRequest, $validated, $villageSetting) {
            $viewName = 'pdf.surat';
            $kode = strtolower($suratRequest->suratType->kode ?? '');
            if ($kode && view()->exists("pdf.surat-{$kode}")) {
                $viewName = "pdf.surat-{$kode}";
            }

            // Generate PDF
            $pdf = Pdf::loadView($viewName, [
                'surat' => $suratRequest,
                'desa' => $villageSetting,
                'nomorSurat' => $validated['nomor_surat'],
                'tanggalSurat' => Carbon::parse($validated['tanggal_surat']),
            ]);

            // Save PDF to public storage
            $fileName = 'surat_' . $suratRequest->id . '_' . time() . '.pdf';
            $filePath = 'surat/' . $fileName;
            Storage::disk('public')->put($filePath, $pdf->output());

            // Update suratRequest record
            $suratRequest->update([
                'nomor_surat' => $validated['nomor_surat'],
                'tanggal_surat' => $validated['tanggal_surat'],
                'file_surat' => $filePath,
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Record status history
            $suratRequest->statusHistories()->create([
                'user_id' => $request->user()->id,
                'status' => 'completed',
                'catatan' => 'Surat resmi telah diterbitkan dengan nomor: ' . $validated['nomor_surat'],
            ]);

            // Perbarui counter penomoran pada jenis surat
            if ($suratRequest->suratType) {
                $suratRequest->suratType->recordUsedNumber($validated['nomor_surat']);
            }
        });

        return redirect()
            ->route('admin.permohonan.show', $suratRequest)
            ->with('success', 'Surat berhasil diterbitkan dan siap diunduh warga.');
    }
}

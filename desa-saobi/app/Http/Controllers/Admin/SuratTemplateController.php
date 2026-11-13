<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuratRequest;
use App\Models\SuratTemplate;
use App\Models\SuratType;
use App\Models\VillageSetting;
use App\Support\SuratTemplateRenderer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SuratTemplateController extends Controller
{
    public function index()
    {
        $templates = SuratTemplate::withCount('suratTypes')->latest()->get();

        return view('admin.template_surat.index', compact('templates'));
    }

    public function create()
    {
        $variableGroups = SuratTemplateRenderer::availableVariables();
        $defaultKonten = $this->defaultKonten();

        return view('admin.template_surat.create', compact('variableGroups', 'defaultKonten'));
    }

    public function store(Request $request)
    {
        SuratTemplate::create($this->validateTemplate($request));

        return redirect()->route('admin.template-surat.index')
            ->with('success', 'Template isi surat berhasil ditambahkan.');
    }

    public function edit(SuratTemplate $template_surat)
    {
        $variableGroups = SuratTemplateRenderer::availableVariables();

        return view('admin.template_surat.edit', [
            'template' => $template_surat,
            'variableGroups' => $variableGroups,
        ]);
    }

    public function update(Request $request, SuratTemplate $template_surat)
    {
        $template_surat->update($this->validateTemplate($request, $template_surat->id));

        return redirect()->route('admin.template-surat.index')
            ->with('success', 'Template isi surat berhasil diperbarui.');
    }

    public function destroy(SuratTemplate $template_surat)
    {
        if ($template_surat->suratTypes()->exists()) {
            return back()->with('error', 'Template ini masih digunakan oleh jenis surat, sehingga tidak dapat dihapus.');
        }

        $template_surat->delete();

        return back()->with('success', 'Template isi surat berhasil dihapus.');
    }

    /**
     * Tampilkan pratinjau surat lengkap dari isi template yang dikirim editor.
     */
    public function preview(Request $request)
    {
        $konten = (string) $request->input('konten', '');
        $judul = $request->input('judul') ?: 'Surat Keterangan Contoh';

        $desa = VillageSetting::first() ?? new VillageSetting([
            'nama_desa' => 'Desa Saobi',
            'nama_kepala_desa' => 'HOSAINI, SH',
            'nip_kepala_desa' => '-',
            'alamat' => 'Jalan Raya Masjid No. 50. Email:desasaobi90@gmail.com Kode Pos 69491',
        ]);

        $surat = new SuratRequest([
            'data_pemohon' => [
                'nama' => 'Ahmad Warga Saobi',
                'nik' => '3529012345670001',
                'tempat_lahir' => 'Sumenep',
                'tanggal_lahir' => '1995-04-12',
                'jenis_kelamin' => 'Laki-laki',
                'status_perkawinan' => 'Kawin',
                'agama' => 'Islam',
                'pekerjaan' => 'Nelayan',
                'dusun' => 'Saobi Timur',
                'rt' => '01',
                'rw' => '02',
                'alamat' => 'Dusun Saobi Timur RT 01 / RW 02',
                'keperluan' => 'Melengkapi persyaratan administrasi',
            ],
            'verification_token' => 'contoh-token-pratinjau',
        ]);
        $surat->setRelation('suratType', new SuratType(['nama' => $judul, 'kode' => 'CONTOH']));

        $nomorSurat = '470/01/CONTOH/' . date('Y');
        $tanggalSurat = Carbon::now();

        $isiSurat = SuratTemplateRenderer::render(
            $konten,
            SuratTemplateRenderer::variables($surat, $desa, $nomorSurat, $tanggalSurat)
        );

        return response()->view('pdf.surat-dinamis', [
            'surat' => $surat,
            'desa' => $desa,
            'nomorSurat' => $nomorSurat,
            'tanggalSurat' => $tanggalSurat,
            'isiSurat' => $isiSurat,
        ]);
    }

    private function validateTemplate(Request $request, ?int $ignoreId = null): array
    {
        $namaRule = Rule::unique('surat_templates', 'nama');
        if ($ignoreId) {
            $namaRule->ignore($ignoreId);
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', $namaRule],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'konten' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['konten'] = $validated['konten'] ?? '';

        return $validated;
    }

    private function defaultKonten(): string
    {
        return implode("\n", [
            'Yang bertanda tangan di bawah ini Kepala {nama_desa}, Kecamatan Kangayan, Kabupaten Sumenep, dengan ini menerangkan dengan sebenarnya bahwa:',
            '',
            '{tabel_pemohon}',
            '',
            'Adalah benar warga yang berdomisili di alamat tersebut di atas.',
            '',
            'Surat keterangan ini dibuat untuk keperluan {keperluan}.',
            '',
            'Demikian surat keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.',
        ]);
    }
}

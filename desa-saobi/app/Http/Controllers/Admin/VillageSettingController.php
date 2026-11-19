<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VillageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VillageSettingController extends Controller
{
    public function index()
    {
        $setting = VillageSetting::first() ?? new VillageSetting([
            'nama_desa' => 'Desa Saobi',
        ]);

        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_desa' => ['required', 'string', 'max:255'],
            'kecamatan' => ['nullable', 'string', 'max:255'],
            'kabupaten' => ['nullable', 'string', 'max:255'],
            'provinsi' => ['nullable', 'string', 'max:255'],
            'nama_kepala_desa' => ['required', 'string', 'max:255'],
            'nip_kepala_desa' => ['nullable', 'string', 'max:50'],
            'alamat' => ['required', 'string', 'max:500'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'string', 'max:255'],
            'logo_kiri' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'logo_kanan' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'gambar_tanda_tangan' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        $setting = VillageSetting::first() ?? new VillageSetting();

        $setting->fill([
            'nama_desa' => $validated['nama_desa'],
            'kecamatan' => $validated['kecamatan'] ?? null,
            'kabupaten' => $validated['kabupaten'] ?? null,
            'provinsi' => $validated['provinsi'] ?? null,
            'nama_kepala_desa' => $validated['nama_kepala_desa'],
            'nip_kepala_desa' => $validated['nip_kepala_desa'] ?? null,
            'alamat' => $validated['alamat'],
            'kode_pos' => $validated['kode_pos'] ?? null,
            'telepon' => $validated['telepon'] ?? null,
            'email' => $validated['email'] ?? null,
            'website' => $validated['website'] ?? null,
        ]);

        $this->storeImage($request, $setting, 'logo_kiri');
        $this->storeImage($request, $setting, 'logo_kanan');
        $this->storeImage($request, $setting, 'gambar_tanda_tangan');

        $setting->save();

        return back()->with('success', 'Pengaturan profil desa, wilayah, dan kop surat berhasil diperbarui.');
    }

    private function storeImage(Request $request, VillageSetting $setting, string $field): void
    {
        if (!$request->hasFile($field)) {
            return;
        }

        if ($setting->{$field} && Storage::disk('public')->exists($setting->{$field})) {
            Storage::disk('public')->delete($setting->{$field});
        }

        $setting->{$field} = $request->file($field)->store('desa', 'public');
    }
}

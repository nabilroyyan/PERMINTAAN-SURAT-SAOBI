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
            'nama_kepala_desa' => 'MOH. SALEH, S.Pd',
            'nip_kepala_desa' => '198205122010011005',
            'alamat' => 'Jl. Pelabuhan Saobi, Pulau Saobi, Kec. Kangayan, Kab. Sumenep, Jawa Timur 69491',
        ]);

        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_desa' => ['required', 'string', 'max:255'],
            'nama_kepala_desa' => ['required', 'string', 'max:255'],
            'nip_kepala_desa' => ['nullable', 'string', 'max:50'],
            'alamat' => ['required', 'string', 'max:500'],
            'gambar_tanda_tangan' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        $setting = VillageSetting::first();

        if (!$setting) {
            $setting = new VillageSetting();
        }

        $setting->nama_desa = $validated['nama_desa'];
        $setting->nama_kepala_desa = $validated['nama_kepala_desa'];
        $setting->nip_kepala_desa = $validated['nip_kepala_desa'];
        $setting->alamat = $validated['alamat'];

        if ($request->hasFile('gambar_tanda_tangan')) {
            // Hapus gambar lama jika ada
            if ($setting->gambar_tanda_tangan && Storage::disk('public')->exists($setting->gambar_tanda_tangan)) {
                Storage::disk('public')->delete($setting->gambar_tanda_tangan);
            }

            $path = $request->file('gambar_tanda_tangan')->store('desa', 'public');
            $setting->gambar_tanda_tangan = $path;
        }

        $setting->save();

        return back()->with('success', 'Data profil Kepala Desa dan Tanda Tangan berhasil diperbarui.');
    }
}

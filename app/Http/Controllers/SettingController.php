<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;


class SettingController extends Controller
{
    public function index()
    {
        return view('setting.index');
    }

    public function show()
    {
        return Setting::first();
    }

    public function update(Request $request)
    {
        $setting = Setting::first();
        $setting->nama_perusahaan = $request->nama_perusahaan;
        $setting->telepon = $request->telepon;
        $setting->alamat = $request->alamat;
        $setting->timezone = $request->timezone;
        $setting->latitude = $request->latitude;
        $setting->longitude = $request->longitude;
        $setting->diskon = $request->diskon;
        $setting->tipe_nota = $request->tipe_nota;

        if ($request->hasFile('path_logo')) {
            $file = $request->file('path_logo');
            $nama = 'logo-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/img'), $nama);

            // Hapus logo sebelumnya jika ada
            if ($setting->path_logo != '/img/logo.png') {
                $path = public_path($setting->path_logo);
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            $setting->path_logo = "/img/$nama";
        }

        if ($request->hasFile('path_kartu_member')) {
            $file = $request->file('path_kartu_member');
            $nama = 'logo-' . date('Y-m-dHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/img'), $nama);

            // Hapus kartu member sebelumnya jika ada
            if ($setting->path_kartu_member != '/img/member.png') {
                $path = public_path($setting->path_kartu_member);
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            $setting->path_kartu_member = "/img/$nama";
        }

        $setting->update();
        

        return response()->json('Data berhasil disimpan', 200);
    }
}

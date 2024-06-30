<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index()
    {
        // Eager load the 'user' relationship
        $presensis = Presensi::with('user')->get();

        // Process the data
        $data = $presensis->map(function ($presensi, $key) {
            $waktuMasuk = Carbon::parse($presensi->waktu_masuk);
            $waktuKeluar = $presensi->waktu_keluar ? Carbon::parse($presensi->waktu_keluar) : null;

            // Calculate total hours and minutes if 'waktu_keluar' is not null
            if ($waktuKeluar) {
                $diff = $waktuMasuk->diff($waktuKeluar);
                $totalJam = $diff->format('%h') + ($diff->format('%i') / 60);
                $totalMenit = $diff->format('%i');
            } else {
                $totalJam = null;
                $totalMenit = null;
            }

            return [
                'no' => $key + 1,
                'id' => $presensi->id,
                'nik' => $presensi->user->nik,
                'nama' => $presensi->user->name,
                'tanggal' => $presensi->tanggal,
                'waktu_masuk' => $waktuMasuk->format('H:i'), // Format time only
                'waktu_keluar' => $waktuKeluar ? $waktuKeluar->format('H:i') : null, // Format time only if 'waktu_keluar' is not null
                'total_jam' => $totalJam !== null ? number_format($totalJam, 0) . ' jam ' . $totalMenit . ' menit' : null, // Concatenate total hours and minutes with "jam" and "menit" if not null
            ];
        });

        return view('presensi.index', ['presensis' => $data]);
    }

    public function edit($id)
    {
        $presensi = Presensi::find($id);
        return view('presensi.edit', ['presensi' => $presensi]);
    }

    public function update(Request $request, $id)
    {
        $presensi = Presensi::find($id);
        $presensi->update($request->all());
        return redirect()->route('presensi.index');
    }

    public function delete($id)
    {
        $presensi = Presensi::find($id);
        $presensi->delete();
        return redirect()->route('presensi.index');
    }
}

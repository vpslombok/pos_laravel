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
        // Eager load the 'user' relationship and sort by tanggal
        $presensis = Presensi::with('user')->orderBy('tanggal', 'asc')->get();

        // Process the data
        $data = $presensis->map(function ($presensi, $key) {
            $waktuMasuk = Carbon::parse($presensi->waktu_masuk);
            $waktuKeluar = $presensi->waktu_keluar ? Carbon::parse($presensi->waktu_keluar) : null;

            // Calculate total hours and minutes if 'waktu_keluar' is not null
            if ($waktuKeluar) {
                $diff = $waktuMasuk->diff($waktuKeluar);
                $totalJam = $diff->format('%h');
                $totalMenit = $diff->format('%i');
                $totalWaktu = "{$totalJam} jam {$totalMenit} menit";
            } else {
                $totalWaktu = null;
            }

            return [
                'no' => $key + 1,
                'id' => $presensi->id,
                'nik' => $presensi->user->nik,
                'nama' => $presensi->user->name,
                'tanggal' => $presensi->tanggal,
                'waktu_masuk' => $waktuMasuk->format('H:i'), // Format time only
                'waktu_keluar' => $waktuKeluar ? $waktuKeluar->format('H:i') : null, // Format time only if 'waktu_keluar' is not null
                'total_jam' => $totalWaktu, // Concatenate total hours and minutes with "jam" and "menit" if not null
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
        $presensi->tanggal = $request->tanggal;
        $presensi->waktu_masuk = $request->waktu_masuk;
        $presensi->waktu_keluar = $request->waktu_keluar;

        // Validasi jika waktu_keluar lebih kecil dari waktu_masuk
        if ($request->waktu_keluar && $request->waktu_keluar < $request->waktu_masuk) {
            return response()->json(['success' => 400, 'message' => 'Waktu pulang tidak boleh lebih awal dari waktu masuk.']);
        }

        $presensi->save();
        return response()->json(['success' => 200, 'message' => 'Data Presensi ' . $presensi->user->name . ' berhasil diupdate']);
    }

    public function delete($id)
    {
        $presensi = Presensi::find($id);
        $presensi->delete();
        return response()->json(['success' => 200, 'message' => 'Data Presensi ' . $presensi->user->name . ' berhasil dihapus']);
    }
}

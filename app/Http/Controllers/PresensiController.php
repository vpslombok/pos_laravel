<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use Carbon\Carbon;
use App\Exports\PresensiExport;
use Maatwebsite\Excel\Facades\Excel;


class PresensiController extends Controller
{
    public function index()
    {
        return view('presensi.index');
    }

    public function data(Request $request)
    {
        $filterDari = $request->input('dari_tanggal');
        $filterSampai = $request->input('sampai_tanggal');

        if ($filterDari && $filterSampai && $filterDari > $filterSampai) {
            return response()->json(['error' => 'Tanggal "Dari" tidak boleh lebih besar dari tanggal "Sampai"'], 400);
        }

        $presensi = Presensi::with('user')
            ->when($filterDari, function ($query) use ($filterDari) {
                return $query->where('tanggal', '>=', $filterDari);
            })
            ->when($filterSampai, function ($query) use ($filterSampai) {
                return $query->where('tanggal', '<=', $filterSampai);
            })
            ->orderBy('tanggal', 'asc')
            ->get();

        return datatables()
            ->of($presensi)
            ->addIndexColumn()
            ->addColumn('nik', function ($presensi) {
                return $presensi->user->nik;
            })
            ->addColumn('nama', function ($presensi) {
                return $presensi->user->name;
            })
            ->addColumn('tanggal', function ($presensi) {
                return Carbon::parse($presensi->tanggal)->format('d-m-Y');
            })
            ->addColumn('waktu_masuk', function ($presensi) {
                return $presensi->waktu_masuk ? Carbon::parse($presensi->waktu_masuk)->format('H:i') : '';
            })
            ->addColumn('waktu_keluar', function ($presensi) {
                return $presensi->waktu_keluar ? Carbon::parse($presensi->waktu_keluar)->format('H:i') : '';
            })
            ->addColumn('total_jam', function ($presensi) {
                if (empty($presensi->waktu_masuk) && empty($presensi->waktu_keluar)) {
                    return '<span style="color: yellow;">Masih Kosong</span>';
                } elseif (empty($presensi->waktu_keluar)) {
                    return '<span style="color: red;">Belum Pulang</span>';
                } else {
                    $waktu_masuk = Carbon::parse($presensi->waktu_masuk);
                    $waktu_keluar = Carbon::parse($presensi->waktu_keluar);
                    $total_jam = $waktu_keluar->diffInHours($waktu_masuk);
                    $total_menit = $waktu_keluar->diffInMinutes($waktu_masuk) % 60;
                    return $total_jam . ' jam ' . $total_menit . ' menit';
                }
            })
            ->addColumn('aksi', function ($presensi) {
                return '
            <div class="btn-group">
                <button type="button" onclick="editModal(`' . route('presensi.edit', $presensi->id) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-pencil"></i></button>
                <button type="button" onclick="deleteData(`' . route('presensi.delete', $presensi->id) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
            </div>
        ';
            })
            ->rawColumns(['aksi', 'total_jam'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        $presensi = Presensi::find($id);
        return response()->json($presensi);
    }

    public function edit($id)
    {
        $presensi = Presensi::with('user')->findOrFail($id);
        $presensi->waktu_masuk = $presensi->waktu_masuk ? Carbon::parse($presensi->waktu_masuk)->format('H:i') : '';
        $presensi->waktu_keluar = $presensi->waktu_keluar ? Carbon::parse($presensi->waktu_keluar)->format('H:i') : '';
        return response()->json($presensi);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $presensi = Presensi::findOrFail($id);
        $waktu_masuk = $request->input('waktu_masuk');
        $waktu_keluar = $request->input('waktu_keluar');

        if ($waktu_keluar && $waktu_masuk && $waktu_keluar < $waktu_masuk) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu pulang tidak boleh lebih kecil dari waktu masuk',
            ], 400);
        }

        $presensi->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data ' . $presensi->user->name . ' berhasil diubah',
        ]);
    }

    public function delete($id)
    {
        $presensi = Presensi::findOrFail($id);
        $presensi->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data ' . $presensi->user->name . ' berhasil dihapus',
        ]);
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new PresensiExport, 'presensi.xlsx');
    }

    public function exportPdf(Request $request)
    {
        return Excel::download(new PresensiExport, 'presensi.pdf');
    }
}

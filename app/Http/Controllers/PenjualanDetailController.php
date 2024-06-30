<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;

class PenjualanDetailController extends Controller
{
    public function index()
    {
        $produk = Produk::orderBy('nama_produk')->get();
        $stok = Produk::orderBy('stok')->get();
        $member = Member::orderBy('nama')->get();
        $diskon = Setting::first()->diskon ?? 0;

        // Cek apakah ada transaksi yang sedang berjalan
        if ($id_penjualan = session('id_penjualan')) {
            $penjualan = Penjualan::find($id_penjualan);
            $memberSelected = $penjualan->member ?? new Member();

            return view('penjualan_detail.index', compact('produk', 'stok', 'member', 'diskon', 'id_penjualan', 'penjualan', 'memberSelected'));
        } else {
            if (auth()->user()->level == 1) {
                return redirect()->route('transaksi.baru');
            } else {
                return redirect()->route('home');
            }
        }
    }


    public function data($id)
    {
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', $id)
            ->get();

        $data = array();
        $total = 0;
        $total_item = 0;
        $total_diskon = 0;

        foreach ($detail as $item) {
            $row = array();
            $row['kode_produk'] = '<span class="label label-success">' . $item->produk['kode_produk'] . '</span';
            $row['barcode'] = '<span class="label label-success">' . $item->produk['barcode'] . '</span';
            $row['nama_produk'] = $item->produk['nama_produk'];
            $row['harga_jual']  = 'Rp. ' . format_uang($item->harga_jual);
            $row['jumlah']      = '<input type="number" class="form-control input-sm quantity" data-id="' . $item->id_penjualan_detail . '" value="' . $item->jumlah . '" data-stok="' . $item->produk['stok'] . '" data-nama-produk="' . $item->produk['nama_produk'] . '">';
            $row['stok']        = $item->produk['stok'];
            $row['diskon']      = 'Rp. ' . format_uang($item->diskon * $item->jumlah);
            $row['subtotal']    = 'Rp. ' . format_uang($item->subtotal);
            $row['aksi']        = '<div class="btn-group">
                                    <button onclick="deleteData(`' . route('transaksi.destroy', $item->id_penjualan_detail) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
                                </div>';
            $data[] = $row;

            $total += $item->harga_jual * $item->jumlah;
            $total_item += $item->jumlah;
            $total_diskon += $item->diskon * $item->jumlah;
        }
        $data[] = [
            'kode_produk' => '
                <div class="total hide">' . $total . '</div>
                <div class="total_item hide">' . $total_item . '</div>
                <div class="total_diskon hide">' . $total_diskon . '</div>',
            'barcode'     => '',
            'nama_produk' => '',
            'harga_jual'  => '',
            'jumlah'      => '',
            'stok'        => '',
            'diskon'      => '',
            'subtotal'    => '',
            'aksi'        => '',
        ];

        return datatables()
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['aksi', 'kode_produk', 'jumlah', 'barcode'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $produk = Produk::where('kode_produk', $request->kode_produk)
            ->orWhere('barcode', $request->kode_produk)
            ->first();

        if (!$produk) {
            return response()->json('PLU atau barcode tidak ada di database', 400);
        }

        if ($produk->stok < 1) {
            return response()->json('Maaf, produk ' . $produk->nama_produk . ' telah habis.', 400);
        }

        $detail = PenjualanDetail::where('id_penjualan', $request->id_penjualan)
            ->where('id_produk', $produk->id_produk)
            ->first();

        if ($detail && $detail->jumlah >= $produk->stok) {
            return response()->json('Maaf, stok produk ' . $produk->nama_produk . ' tidak mencukupi.', 400);
        }

        if ($detail) {
            $detail->jumlah += 1;
            $detail->subtotal = $detail->harga_jual * $detail->jumlah; // Perbaiki perhitungan subtotal
            $detail->update();

            return response()->json('Data berhasil disimpan', 200);
        }

        $nomor_nota = Penjualan::find($request->id_penjualan)->nomor_nota;

        $detail = new PenjualanDetail();
        $detail->id_penjualan = $request->id_penjualan;
        $detail->id_produk = $produk->id_produk;
        $detail->harga_jual = $produk->harga_jual;
        $detail->jumlah = 1;
        $detail->diskon = $produk->diskon;
        $detail->subtotal = $produk->harga_jual - $detail->diskon * $detail->jumlah; // Perbaiki perhitungan subtotal
        $detail->nomor_nota = $nomor_nota;
        $detail->save();

        return response()->json('Data berhasil disimpan', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->jumlah = $request->jumlah;
        $detail->subtotal = $detail->harga_jual * $request->jumlah - $detail->diskon * $request->jumlah; // Perbaiki perhitungan subtotal
        $detail->update();
    }


    public function destroy($id)
    {
        $detail = PenjualanDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($diskon = 0, $total = 0, $diterima = 0, $total_diskon = 0)
    {
        $bayar   = $total - $diskon - $total_diskon;
        $kembali = ($diterima != 0) ? $diterima - $bayar : 0;
        $data    = [
            'totalrp' => format_uang($total),
            'bayar' => $bayar,
            'diskon' => $diskon,
            'total_diskon' => format_uang($total_diskon + $diskon),
            'bayarrp' => format_uang($bayar),
            'terbilang' => ucwords(terbilang($bayar) . ' Rupiah'),
            'kembalirp' => format_uang($kembali),
            'kembali_terbilang' => ucwords(terbilang($kembali) . ' Rupiah'),
        ];

        return response()->json($data);
    }
}

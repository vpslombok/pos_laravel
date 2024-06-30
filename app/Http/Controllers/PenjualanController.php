<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Member;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use App\Models\Setting;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Str;

class PenjualanController extends Controller
{
    public function index()
    {
        return view('penjualan.index');
    }

    public function data()
    {
        $penjualan = Penjualan::with('member')
            ->orderBy('id_penjualan', 'desc')
            ->whereNotNull('nomor_nota')
            ->where('total_item', '>', 0) // Filter penjualan dengan total_item > 0
            ->get();

        return datatables()
            ->of($penjualan)
            ->addIndexColumn()
            ->addColumn('select_all', function ($penjualan) {
                return '
                <input type="checkbox" name="id_penjualan[]" value="' . $penjualan->id_penjualan . '">
            ';
            })
            ->addColumn('total_item', function ($penjualan) {
                return format_uang($penjualan->total_item);
            })
            ->addColumn('total_harga', function ($penjualan) {
                return 'Rp. ' . format_uang($penjualan->total_harga);
            })
            ->addColumn('bayar', function ($penjualan) {
                return 'Rp. ' . format_uang($penjualan->bayar);
            })
            ->addColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->created_at, false);
            })
            ->addColumn('nama_member', function ($penjualan) {
                $member = $penjualan->member->nama ?? '';
                return '<span class="label label-success">' . $member . '</spa>';
            })
            ->editColumn('diskon', function ($penjualan) {
                return 'Rp.' . format_uang($penjualan->diskon);
            })
            ->editColumn('total_diskon', function ($penjualan) {
                return 'Rp.' . format_uang($penjualan->total_diskon);
            })
            ->editColumn('kasir', function ($penjualan) {
                return $penjualan->user->name ?? '';
            })
            ->addColumn('aksi', function ($penjualan) {
                return '
            <div class="btn-group">
                <button onclick="showDetail(`' . route('penjualan.show', $penjualan->id_penjualan) . '`)" class="btn btn-xs btn-info btn-flat"><i class="fa fa-eye"></i></button>
                <button onclick="deleteData(`' . route('penjualan.destroy', $penjualan->id_penjualan) . '`)" class="btn btn-xs btn-danger btn-flat"><i class="fa fa-trash"></i></button>
            </div>
            ';
            })
            ->rawColumns(['aksi', 'nama_member', 'select_all'])
            ->make(true);
    }

    public function create(Request $request)
    {
        // Ambil ID pengguna yang sedang login
        $userId = auth()->id();

        // Cari penjualan yang masih kosong untuk pengguna yang sedang login
        $penjualan = Penjualan::where('id_user', $userId)
            ->where('total_item', 0)
            ->where('total_harga', 0)
            ->where('diskon', 0)
            ->where('total_diskon', 0)
            ->where('bayar', 0)
            ->where('diterima', 0)
            ->first();

        // Jika penjualan kosong ditemukan, gunakan penjualan tersebut
        if ($penjualan) {
            session(['id_penjualan' => $penjualan->id_penjualan]);
        } else {
            // Jika tidak, buat penjualan baru
            $penjualan = new Penjualan();
            $penjualan->id_member = $request->id_member;
            $penjualan->nomor_nota = $this->buatNomorNota();
            $penjualan->total_item = 0;
            $penjualan->total_harga = 0;
            $penjualan->diskon = 0;
            $penjualan->total_diskon = 0;
            $penjualan->bayar = 0;
            $penjualan->diterima = 0;
            $penjualan->id_user = $userId;
            $penjualan->save();

            session(['id_penjualan' => $penjualan->id_penjualan]);
        }

        return redirect()->route('transaksi.index');
    }


    // Fungsi baru untuk membuat nomor nota
    public function buatNomorNota()
    {
        $bulanTahun = date('my');
        $angkaAcak = strtoupper(Str::random(4));
        $nomorNota = $bulanTahun . $angkaAcak;

        return $nomorNota;
    }

    public function store(Request $request)
    {
        $penjualan = Penjualan::findOrFail($request->id_penjualan);
        $penjualan->id_member = $request->id_member;
        $penjualan->total_item = $request->total_item;
        $penjualan->total_harga = $request->total;
        $penjualan->diskon = $request->diskon;
        $penjualan->total_diskon = str_replace('.', '', $request->total_diskon);
        $penjualan->bayar = $request->bayar;
        $penjualan->diterima = $request->diterima;
        $penjualan->created_at = date('Y-m-d H:i:s');
        $penjualan->update();

        $detail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $item->diskon = $request->diskon;
            $item->update();

            $produk = Produk::find($item->id_produk);
            $produk->stok -= $item->jumlah;
            $produk->update();
        }

        return redirect()->route('transaksi.selesai');
    }

    public function show($id)
    {
        $detail = PenjualanDetail::with('produk')->where('id_penjualan', $id)->get();

        foreach ($detail as $item) {
            if (empty($item->produk->id_produk)) {
                $item->delete();
            }
        }

        return datatables()
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($detail) {
                return '<span class="label label-success">' . $detail->produk->kode_produk . '</span>';
            })
            ->addColumn('nama_produk', function ($detail) {
                return $detail->produk->nama_produk;
            })
            ->addColumn('harga_jual', function ($detail) {
                return 'Rp. ' . format_uang($detail->harga_jual);
            })
            ->addColumn('jumlah', function ($detail) {
                return format_uang($detail->jumlah);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. ' . format_uang($detail->subtotal);
            })
            ->rawColumns(['kode_produk'])
            ->make(true);
    }

    public function destroy($id)
    {
        $penjualan = Penjualan::find($id);
        $detail    = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($detail as $item) {
            $produk = Produk::find($item->id_produk);
            if ($produk) {
                $produk->stok += $item->jumlah;
                $produk->update();
            }

            $item->delete();
        }

        $penjualan->delete();

        return response(null, 204);
    }

    public function updateNomorNota()
    {
        $nomorNota = Penjualan::where('id_penjualan', session('id_penjualan'))->pluck('nomor_nota')->first() ?? 'belum ada transaksi';
        return $nomorNota;
    }

    public function selesai()
    {
        $setting = Setting::first();

        return view('penjualan.selesai', compact('setting'));
    }
    public function datamember()
    {
        // Lakukan join antara Penjualan dan members
        $member = Penjualan::join('member', 'penjualan.id_member', '=', 'member.id_member')
            ->where('penjualan.id_penjualan', session('id_penjualan'))
            ->pluck('member.nama')
            ->first();

        // Ubah logika untuk menangani jika data member tidak ditemukan
        if ($member) {
            // Kembalikan nama member jika ditemukan
            return $member;
        } else {
            // Jika tidak ditemukan, kembalikan pesan 'Tidak ada data member'
            return 'Tidak ada data';
        }
    }

    public function postMember(Request $request)
    {
        $penjualan = Penjualan::find(session('id_penjualan'));

        // Inisialisasi id_member dengan null
        $id_member = null;

        // Periksa apakah request memiliki kode_member dan tidak kosong
        if ($request->has('kode_member') && $request->kode_member) {
            // Cari id_member berdasarkan telepon atau kode member
            $id_member = Member::where('telepon', $request->kode_member)
                ->orWhere('kode_member', $request->kode_member)
                ->pluck('id_member')
                ->first();
        }

        // Simpan id_member ke dalam penjualan
        $penjualan->id_member = $id_member;

        // Simpan perubahan
        $penjualan->update();
    }

    public function cetakNota(Request $request)
    {
        //ambil inputan nomor nota dari request dan ubah memjadi session id_penjualan 
        session(['id_penjualan' => Penjualan::where('nomor_nota', $request->nomor_nota)->pluck('id_penjualan')->first()]);
        //cek apakah id_penjualan ada
        if (!session('id_penjualan')) {
            return redirect()->back()->with('error', 'Nomor nota tidak ditemukan');
        }
    }

    public function notaKecil(Request $request)
    {

        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (!$penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }

    public function cetakulang(Request $request)
    {

        $setting = Setting::first();
        $penjualan = Penjualan::find(session(''));
        if (!$penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        return view('penjualan.nota_kecil', compact('setting', 'penjualan', 'detail'));
    }

    public function notaBesar()
    {
        $setting = Setting::first();
        $penjualan = Penjualan::find(session('id_penjualan'));
        if (!$penjualan) {
            abort(404);
        }
        $detail = PenjualanDetail::with('produk')
            ->where('id_penjualan', session('id_penjualan'))
            ->get();

        $pdf = PDF::loadView('penjualan.nota_besar', compact('setting', 'penjualan', 'detail'));
        $pdf->setPaper(0, 0, 609, 440, 'potrait');
        return $pdf->stream('Transaksi-' . date('Y-m-d-his') . '.pdf');
    }
    public function penjualan_produk(Request $request)
    {
        $tanggalAwal = $request->tanggal_awal ?? date('Y-m-d', strtotime('first day of this month'));
        $tanggalAkhir = $request->tanggal_akhir ?? date('Y-m-d');

        $penjualanProduk = PenjualanDetail::select(
            DB::raw('date(penjualans.created_at) as tanggal'),
            DB::raw('sum(penjualan_details.jumlah * penjualan_details.harga_jual) as total_penjualan')
        )
            ->join('penjualans', 'penjualans.id', '=', 'penjualan_details.id_penjualan')
            ->whereBetween('penjualans.created_at', [$tanggalAwal, $tanggalAkhir])
            ->groupBy(DB::raw('date(penjualans.created_at)'))
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('penjualan.penjualan_produk', compact('penjualanProduk', 'tanggalAwal', 'tanggalAkhir'));
    }
}

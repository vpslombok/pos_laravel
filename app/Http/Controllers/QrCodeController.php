<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ValidQrCode;


class QrCodeController extends Controller
{
    public function generateQrCode(Request $request)
    {
        if ($request->has('id')) {
            $id = $request->id;

            // Hapus QR code lama dari database
            ValidQrCode::where('created_at', '<', now()->subSeconds(30))->delete();

            // Simpan QR code baru dengan tanggal dari .env
            $tanggal = now();
            ValidQrCode::create(['code' => $id, 'created_at' => $tanggal]);

            // Simpan ID QR code ke dalam sesi
            Session::put('qr_code_id', $id);

            return response()->json(['success' => true, 'id' => $id]);
        } else {
            // Mulai atau lanjutkan sesi
            if (!Session::has('qr_code_id')) {
                // Jika belum, generate ID acak untuk QR code
                $id = uniqid();

                // Simpan ID QR code ke dalam sesi
                Session::put('qr_code_id', $id);

                // Simpan ID QR code ke dalam database valid_qr_codes dengan tanggal dari .env
                $tanggal = env('APP_TIMEZONE');
                ValidQrCode::create(['code' => $id, 'created_at' => $tanggal]);
            } else {
                // Jika sudah, gunakan ID QR code yang ada dalam sesi
                $id = Session::get('qr_code_id');
            }

            return response()->json(['success' => true, 'id' => $id]);
        }
    }
}

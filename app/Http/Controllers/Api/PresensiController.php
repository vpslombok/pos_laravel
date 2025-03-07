<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PresensiController extends Controller
{
    public function index($id = null)
    {
        if ($id) {
            $presensi = Presensi::where('user_id', $id)->get();
        } else {
            $user = Auth::user();
            $presensi = Presensi::where('user_id', $user->id)->get();
        }

        return response()->json($presensi);
    }

    public function datauser()
    {
        $user = User::all();
        return response()->json($user);
    }
}

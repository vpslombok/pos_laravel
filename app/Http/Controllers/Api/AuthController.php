<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('nik', 'password'))) {
            return response()->json([
                'message' => 'Login Gagal'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'name' => $user->name,
                'nik' => $user->nik,
                'email' => $user->email,
            ],
        ]);
    }

    public function datauser()
    {
        $user = User::all();
        return response()->json([
            'users' => $user,
        ]);
    }
}

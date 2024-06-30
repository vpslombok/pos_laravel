<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Presensi;

class CheckIfUserHasNotCheckedIn
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Cek apakah user sudah melakukan presensi hari ini
        $hasCheckedIn = Presensi::where('user_id', $user->id)
                                ->whereDate('created_at', now()->toDateString())
                                ->exists();

        if (!$hasCheckedIn) {
            return redirect()->back()->withErrors(['error' => 'Anda belum melakukan presensi hari ini.']);
        }

        return $next($request);
    }
}

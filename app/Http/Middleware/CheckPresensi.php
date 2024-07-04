<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPresensi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && !$user->hasPresensiToday()) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'presensi' => 'Anda harus melakukan presensi setiap hari sebelum login.',
            ]);
        }

        return $next($request);
    }
}

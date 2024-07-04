<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return redirect()->route('login')->withErrors([
                'password' => 'Nik atau Password Salah.',
            ]);
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        $user = Auth::user();
        if ($user && !$user->hasPresensiToday()) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'presensi' => 'Anda Belum Presensi Hari Ini.',
            ]);
        }

        return $next($request);
    }
}


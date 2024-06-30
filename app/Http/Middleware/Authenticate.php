<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Mendapatkan path yang harus diarahkan pengguna ketika mereka tidak terautentikasi.
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return redirect()->route('login');
        }
    }
}

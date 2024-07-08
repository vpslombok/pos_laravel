<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class DatabasesettingController extends Controller
{
    public function index()
    {
        // Mengambil file backup dari direktori storage/app/
        $backupFiles = Storage::disk('local')->files(env('APP_NAME'));
        return view('setting.database_setting', ['backups' => $backupFiles]);
    }
}

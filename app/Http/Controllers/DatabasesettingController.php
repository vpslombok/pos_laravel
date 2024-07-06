<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DatabasesettingController extends Controller
{
    public function index()
    {
        return view('setting.database_setting');
    }
}

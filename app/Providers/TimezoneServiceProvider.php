<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use App\Models\Setting;

class TimezoneServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $timezone = DB::table('setting')->where('id_setting', 'timezone')->value('timezone');
        if ($timezone) {
            Config::set('app.timezone', $timezone);
        } else {
            // Log or handle the case where timezone is not found in the database
            Config::set('app.timezone', 'UTC'); // Default fallback
        }
    }
}

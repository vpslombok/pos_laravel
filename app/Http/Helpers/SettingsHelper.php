<?php

namespace App\Http\Helpers;
use App\Models\Setting;

class SettingsHelper
{
    public static function getTimezone()
    {
        $setting = Setting::first();
        return $setting ? $setting->timezone : config('app.fallback_timezone', 'UTC');
    }
}

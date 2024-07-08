<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $users = \App\Models\User::all();
            $tanggal = \Carbon\Carbon::now()->toDateString();

            foreach ($users as $user) {
                if (!\App\Models\Presensi::where('user_id', $user->id)->where('tanggal', $tanggal)->exists()) {
                    $presensi = new \App\Models\Presensi();
                    $presensi->user_id = $user->id;
                    $presensi->tanggal = $tanggal;
                    $presensi->waktu_masuk = null;
                    $presensi->waktu_keluar = null;
                    $presensi->save();
                }
            }
        })->everyMinute(); // Jalankan setiap menit
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

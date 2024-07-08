<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use App\Models\Presensi;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function scopeIsNotAdmin($query)
    {
        return $query->where('level', '!=', 1);
    }

    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    // Metode untuk memeriksa apakah pengguna sudah melakukan presensi hari ini
    public function hasPresensiToday()
    {
        return $this->presensis()->whereDate('tanggal', now()->toDateString())
            ->whereNotNull('waktu_masuk')
            ->exists();
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $tanggal = Carbon::now()->toDateString();
            if (!Presensi::where('user_id', $user->id)->where('tanggal', $tanggal)->exists()) {
                $presensi = new Presensi();
                $presensi->user_id = $user->id;
                $presensi->tanggal = $tanggal;
                $presensi->waktu_masuk = null;
                $presensi->waktu_keluar = null;
                $presensi->save();
            }
        });
    }
}

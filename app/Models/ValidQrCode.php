<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidQrCode extends Model
{
    use HasFactory;

    protected $table = 'valid_qr_codes';

    protected $fillable = [
        'code',
        'created_at',
    ];
    public $timestamps = false;
}



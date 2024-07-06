<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('valid_qr_codes', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('code', 225);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrent();
        });
    }
};

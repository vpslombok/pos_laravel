<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiredAtToPasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('password_resets', function (Blueprint $table) {
            $table->timestamp('expired_at')->nullable();
        });
        Schema::table('pembelian', function (Blueprint $table) {
            $table->integer('total_diskon')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('password_resets', function (Blueprint $table) {
            $table->dropColumn('expired_at');
        });
        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropColumn('total_diskon');
        });
    }
}


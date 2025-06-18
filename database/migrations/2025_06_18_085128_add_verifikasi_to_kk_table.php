<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerifikasiToKkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::table('kk', function (Blueprint $table) {
        $table->enum('verifikasi', ['pending', 'acc'])->default('pending');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
   public function down()
{
    Schema::table('kk', function (Blueprint $table) {
        $table->dropColumn('verifikasi');
    });
}
}

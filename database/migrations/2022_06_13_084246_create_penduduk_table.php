<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendudukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penduduk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('nik')->unique();
            $table->unsignedBigInteger('rw_id');
            $table->foreign('rw_id')->references('id')->on('rw')->onDelete('cascade');
            $table->unsignedBigInteger('rt_id');
            $table->foreign('rt_id')->references('id')->on('rt')->onDelete('cascade');
            $table->unsignedBigInteger('kk_id');
            $table->foreign('kk_id')->references('id')->on('kk')->onDelete('cascade');
            $table->enum('gender',['Perempuan','Laki-laki']);
            $table->string('usia');
            $table->string('tmp_lahir');
            $table->date('tgl_lahir');
            $table->enum('agama',['Islam','Katolik','Protestan','Konghucu','Buddha','Hindu']);
            $table->longtext('alamat');
            $table->enum('status_pernikahan',['Kawin','Belum Kawin','Cerai']);
            $table->enum('status_keluarga',['Kepala Rumah Tangga','Isteri','Anak','Lainnya']);
            $table->string('pekerjaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penduduk');
    }
}

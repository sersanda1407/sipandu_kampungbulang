<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kepala_keluarga');
            $table->string('image');
            $table->string('no_kk', 16);
            $table->unsignedBigInteger('rt_id');
            $table->foreign('rt_id')->references('id')->on('rt')->onDelete('cascade');
            $table->unsignedBigInteger('rw_id');
            $table->foreign('rw_id')->references('id')->on('rw')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status_ekonomi',['Mampu','Tidak Mampu']);
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
        Schema::dropIfExists('kk');
    }
}

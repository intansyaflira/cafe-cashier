<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_tabel', function (Blueprint $table) {
            $table->bigIncrements('id_transaksi');
            $table->date('tgl_transaksi');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_meja');
            $table->string('nama_pelanggan');
            $table->enum('status', ['belum_bayar', 'lunas']);

            $table->foreign('id_user')->references('id_user')->on('users');
            $table->foreign('id_meja')->references('id_meja')->on('meja');
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
        Schema::dropIfExists('transaksi_tabel');
    }
};

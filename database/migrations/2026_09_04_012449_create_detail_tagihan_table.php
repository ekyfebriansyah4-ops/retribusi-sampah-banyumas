<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_tagihan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tagihan_id')->constrained('tagihan');
            $table->string('kode_bayar')->unique();
            $table->date('tanggalbilling')->nullable();
            $table->date('tanggalexpired')->nullable();
            $table->integer('nilai');
            $table->integer('bunga')->default(0);
            $table->string('truck')->nullable();
            $table->string('netto')->nullable();
            $table->string('skrd')->nullable();
            $table->date('tanggal_bayar')->nullable();
            $table->string('bukti')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_tagihan');
    }
};
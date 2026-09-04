<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_rdf', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detail_tagihan_id')->constrained('detail_tagihan');
            $table->string('jenis');
            $table->string('qty');
            $table->integer('harga');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_rdf');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('detail_tagihan', function (Blueprint $table) {
            $table->string('qris_reference')->nullable();
            $table->string('qris_status')->default('belum_dibuat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_tagihan', function (Blueprint $table) {
            $table->dropColumn(['qris_reference', 'qris_status']);
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('role')->default('user');
            $table->foreignId('golongan_tarif_id')->nullable()->constrained('golongan_tarif');
            $table->string('status')->default('aktif');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['golongan_tarif_id']);
            $table->dropColumn(['alamat', 'no_hp', 'role', 'golongan_tarif_id', 'status']);
        });
    }
};
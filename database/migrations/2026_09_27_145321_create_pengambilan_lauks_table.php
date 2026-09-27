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
        Schema::create('pengambilan_lauks', function (Blueprint $table) {
            $table->id('idPengambilan');
            $table->foreignId('santri_id')->constrained('santris', 'idSantri')->onDelete('cascade');
            $table->foreignId('catering_id')->nullable()->constrained('caterings', 'idCatering')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('waktu_ambil')->nullable();
            $table->string('status_ambil')->default('Sudah Ambil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengambilan_lauks');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id('idPresensi');
            $table->foreignId('santri_id')->constrained('santris', 'idSantri')->onDelete('cascade');
            $table->foreignId('catering_id')->constrained('caterings', 'idCatering')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
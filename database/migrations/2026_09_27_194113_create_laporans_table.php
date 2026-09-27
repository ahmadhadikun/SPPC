<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->increments('idLaporan');

            $table->unsignedInteger('idPengguna');

            $table->date('tanggal');
            $table->string('jenis');

            $table->foreign('idPengguna')
                ->references('idPengguna')
                ->on('penggunas')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
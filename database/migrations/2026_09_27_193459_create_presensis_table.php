<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->increments('idPresensi');

            $table->unsignedInteger('idSantri');
            $table->unsignedInteger('idCatering');

            $table->date('tanggal');
            $table->time('waktu');
            $table->string('status');

            $table->foreign('idSantri')
                ->references('idSantri')
                ->on('santris')
                ->cascadeOnDelete();

            $table->foreign('idCatering')
                ->references('idCatering')
                ->on('caterings')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
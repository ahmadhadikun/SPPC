<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengambilan_lauks', function (Blueprint $table) {
            $table->increments('idPengambilan');

            $table->unsignedInteger('idSantri');
            $table->unsignedInteger('idCatering');

            $table->dateTime('waktuAmbil');
            $table->boolean('statusAmbil')->default(true);

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
        Schema::dropIfExists('pengambilan_lauks');
    }
};
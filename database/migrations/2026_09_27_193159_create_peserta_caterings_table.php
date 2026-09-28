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
        Schema::create('peserta_caterings', function (Blueprint $table) {
            $table->increments('idPeserta');

            $table->unsignedInteger('idSantri')->unique();

            $table->string('periode');
            $table->boolean('status')->default(true);

            $table->foreign('idSantri')
                ->references('idSantri')
                ->on('santris')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_caterings');
    }
};
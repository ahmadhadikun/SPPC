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
            $table->id('idPeserta');
            $table->foreignId('santri_id')->constrained('santris', 'idSantri')->onDelete('cascade');
            $table->string('periode');
            $table->boolean('status')->default(true);
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
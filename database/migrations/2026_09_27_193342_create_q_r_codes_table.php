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
        Schema::create('qrcodes', function (Blueprint $table) {
            $table->increments('idQR');

            $table->unsignedInteger('idSantri')->unique();

            $table->string('kodeQR')->unique();
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
        Schema::dropIfExists('qrcodes');
    }
};
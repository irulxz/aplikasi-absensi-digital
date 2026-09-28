<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('peserta_didik_id');
            $table->unsignedInteger('jadwal_id')->nullable();
            $table->date('tanggal');
            $table->string('status');
            $table->timestamps();

            $table->foreign('peserta_didik_id')
                ->references('id')
                ->on('peserta_didik')
                ->onDelete('cascade');

            $table->foreign('jadwal_id')
                ->references('id')
                ->on('jadwals')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
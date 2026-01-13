<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('pesertas')->cascadeOnDelete();

            $table->integer('nominal');
            $table->enum('metode', ['tunai', 'transfer']);
            $table->date('tanggal');

            // status verifikasi admin
            $table->enum('status', ['pending', 'diterima'])->default('pending');

            $table->string('bukti_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};

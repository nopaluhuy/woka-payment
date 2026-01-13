<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pesertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('jenis', ['kursus', 'pkl']);
            $table->string('program')->nullable();
            $table->string('asal_sekolah')->nullable();

            // 🔥 JSON jadwal (bisa pilih banyak hari)
            $table->json('jadwal')->nullable();

            $table->integer('biaya_per_bulan');
            $table->integer('durasi_bulan')->nullable();

            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_keluar')->nullable();
            $table->string('alasan_keluar')->nullable();

            // 🔥 status peserta
            $table->enum('status', ['pending', 'diterima', 'nonaktif'])
                  ->default('pending');

            // 🔥 pembayaran terakhir (kursus)
            $table->timestamp('last_payment_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesertas');
    }
};

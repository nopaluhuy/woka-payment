<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kwitansis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembayaran_id')->constrained('pembayarans')->cascadeOnDelete();
            $table->string('nomor_kwitansi')->unique();
            $table->string('file_pdf');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kwitansis');
    }
};

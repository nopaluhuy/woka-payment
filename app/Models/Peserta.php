<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peserta extends Model
{
    protected $table = 'pesertas';

    protected $fillable = [
        'user_id',
        'jenis',
        'program',
        'asal_sekolah',
        'jadwal',
        'biaya_per_bulan',
        'durasi_bulan',
        'tanggal_masuk',
        'tanggal_keluar',
        'alasan_keluar',
        'status',
        'last_payment_at',
    ];

    // App\Models\Peserta.php
    protected $casts = [
        'jadwal' => 'array',
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
        'last_payment_at' => 'datetime',
    ];


    /* =========================
     | RELATION
     ========================= */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    /* =========================
     | KEUANGAN
     ========================= */

    /**
     * Total biaya (khusus PKL)
     */
    public function totalBiaya(): ?int
    {
        if ($this->jenis === 'pkl' && $this->durasi_bulan && $this->biaya_per_bulan) {
            return $this->durasi_bulan * $this->biaya_per_bulan;
        }

        return null;
    }

    /**
     * Total pembayaran
     */
    public function totalDibayar(): int
    {
        return $this->pembayaran()->sum('nominal');
    }

    /**
     * Sisa pembayaran
     */
    public function sisaPembayaran(): ?int
    {
        if ($this->jenis === 'pkl') {
            return max(0, ($this->totalBiaya() ?? 0) - $this->totalDibayar());
        }

        return null;
    }

    /* =========================
     | STATUS AKTIF
     ========================= */
    public function isActive(): bool
    {
        // 1. Sudah keluar → nonaktif
        if ($this->tanggal_keluar && $this->tanggal_keluar->lte(now())) {
            return false;
        }

        // 2. PKL → aktif selama belum keluar
        if ($this->jenis === 'pkl') {
            return true;
        }

        // 3. Kursus
        // kalau belum ada data pembayaran, anggap masih aktif
        if (!$this->last_payment_at) {
            return true;
        }

        // kalau pembayaran terakhir > 7 hari → nonaktif
        return $this->last_payment_at->gte(
            now()->subDays(7)
        );
    }



    /* =========================
     | AUTO NONAKTIF
     ========================= */
    public function refreshStatus(): void
    {
        if (
            $this->jenis === 'kursus' &&
            $this->status === 'diterima' &&
            $this->last_payment_at &&
            $this->last_payment_at->lt(now()->subDays(7))
        ) {
            $this->update([
                'status' => 'nonaktif'
            ]);
        }
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->isActive() ? 'Aktif' : 'Nonaktif';
    }


    public function getJadwalDisplayAttribute(): string
    {
        if (empty($this->jadwal)) {
            return '-';
        }

        if (is_array($this->jadwal)) {
            return collect($this->jadwal)
                ->map(function ($item) {
                    // kalau format { label: "..." }
                    if (is_array($item) && isset($item['label'])) {
                        return $item['label'];
                    }

                    // fallback kalau string biasa
                    return (string) $item;
                })
                ->implode(', ');
        }

        return (string) $this->jadwal;
    }



}

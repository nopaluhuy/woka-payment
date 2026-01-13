<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'peserta_id',
        'nominal',
        'metode',
        'tanggal',
        'bukti_path',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // =====================
    // RELATION
    // =====================
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // pastikan ada kolom user_id jika dipakai
    }
    public function peserta()
    {
        return $this->belongsTo(Peserta::class, 'peserta_id');
    }

    public function kwitansi()
    {
        return $this->hasOne(Kwitansi::class);
    }

    // =====================
    // HELPER STATUS
    // =====================
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'diterima';
    }
}

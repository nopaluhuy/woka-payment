<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kwitansi extends Model
{
    //
    // table is plural 'kwitansis' per migration
    protected $table = 'kwitansis';


    protected $fillable = ['pembayaran_id', 'nomor_kwitansi', 'file_pdf'];


    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }
}

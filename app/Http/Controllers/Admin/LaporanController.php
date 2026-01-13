<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // total keseluruhan
        $totalAll = Pembayaran::sum('nominal');

        // ringkasan 10 pembayaran terakhir
        $recent = Pembayaran::with('peserta.user')->orderBy('tanggal','desc')->limit(50)->get();

        return view('admin.laporan.index', compact('totalAll','recent'));
    }

    
}

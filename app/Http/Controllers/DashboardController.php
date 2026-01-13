<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403);
        }

        $icons = [
            'all-participants' => '<svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 14a4 4 0 100-8 4 4 0 000 8zM6 20a6 6 0 0112 0H6z"/></svg>',
            'pkl-participants' => '<svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M2 7h20v14H2V7zm5-4h10v4H7V3z"/></svg>',
            'kursus-participants' => '<svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 20h9M12 4h9M3 6h9M3 18h9"/></svg>',
            'payments' => '<svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M2 7h20v14H2V7zm2 4h4v2H4v-2z"/></svg>',
        ];


        // Total peserta
        $totalPeserta = User::where('role', 'peserta')->count();
        $totalPesertaPKL = User::where('role', 'peserta')->whereHas('peserta', fn($q) => $q->where('jenis', 'pkl'))->count();
        $totalPesertaKursus = User::where('role', 'peserta')->whereHas('peserta', fn($q) => $q->where('jenis', 'kursus'))->count();

        // Total pembayaran: angka murni, jangan number_format
        $totalPembayaran = Pembayaran::sum('nominal');
        $totalPembayaran = 'Rp ' . number_format($totalPembayaran, 0, ',', '.');

        return view('admin.dashboard', compact(
            'totalPeserta',
            'totalPesertaPKL',
            'totalPesertaKursus',
            'totalPembayaran',
            'icons'
        ));

        if ($user->role === 'peserta') {
            return view('peserta.index');
        }

        abort(403);
    }


    // API data chart
    public function paymentsData()
    {
        if (auth()->user()->role !== 'admin')
            abort(403);

        $months = [];
        $paymentsPKL = [];
        $paymentsKursus = [];

        for ($i = 0; $i < 12; $i++) {
            $date = now()->startOfYear()->addMonths($i); // Januari → Desember
            $months[] = $date->format('M');

            $paymentsPKL[] = Pembayaran::whereHas('peserta', fn($q) => $q->where('jenis', 'pkl'))
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('nominal');

            $paymentsKursus[] = Pembayaran::whereHas('peserta', fn($q) => $q->where('jenis', 'kursus'))
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('nominal');
        }

        return response()->json([
            'months' => $months,
            'PKL' => $paymentsPKL,
            'Kursus' => $paymentsKursus,
        ]);
    }
}


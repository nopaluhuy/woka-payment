<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peserta;
use Carbon\Carbon;

class CheckLatePayments extends Command
{
    /**
     * Nama command yang dipanggil scheduler
     */
    protected $signature = 'payments:check-late';

    /**
     * Deskripsi command
     */
    protected $description = 'Otomatis menonaktifkan peserta kursus jika telat bayar lebih dari 7 hari setelah jatuh tempo';

    /**
     * Eksekusi command
     */
    public function handle()
    {
        $this->info('🔍 Memeriksa keterlambatan pembayaran peserta kursus...');
        $now   = Carbon::now();
        $count = 0;

        // Ambil peserta kursus yang masih aktif
        $pesertas = Peserta::where('jenis', 'kursus')
            ->where('status', 'aktif')
            ->whereNull('tanggal_keluar')
            ->get();

        foreach ($pesertas as $peserta) {

            // Ambil pembayaran terakhir yang SUDAH DITERIMA
            $lastPaid = $peserta->pembayaran()
                ->where('status', 'diterima')
                ->orderBy('tanggal', 'desc')
                ->first();

            // Jika belum pernah bayar sama sekali → lewati
            if (!$lastPaid || !$lastPaid->tanggal) {
                continue;
            }

            // Jatuh tempo = 1 bulan setelah pembayaran terakhir
            $jatuhTempo = Carbon::parse($lastPaid->tanggal)->addMonth();

            // Batas akhir = jatuh tempo + 7 hari
            $batasAkhir = $jatuhTempo->copy()->addDays(7);

            // Jika sudah lewat batas akhir → NONAKTIF
            if ($now->greaterThan($batasAkhir)) {
                $peserta->update([
                    'status'         => 'nonaktif',
                    'tanggal_keluar' => $now->toDateString(),
                ]);
                $count++;
            }
        }

        $this->info("✅ Selesai. Peserta yang dinonaktifkan: {$count}");
        return 0;
    }
}

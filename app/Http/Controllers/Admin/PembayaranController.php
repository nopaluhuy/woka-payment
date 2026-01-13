<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Peserta;
use App\Models\Kwitansi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    // =========================
    // LIST PEMBAYARAN
    // =========================
    public function index(Request $request)
    {
        $query = Pembayaran::with('peserta.user','kwitansi');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('peserta.user', function ($u) use ($q) {
                $u->where('name','like',"%{$q}%")
                  ->orWhere('email','like',"%{$q}%");
            });
        }

        $totalNominal = (clone $query)->sum('nominal');
        $pembayarans = $query->orderByDesc('tanggal')->paginate(20);

        return view('admin.pembayaran.index', compact('pembayarans','totalNominal'));
    }

    public function indexKursus(Request $request)
    {
        $query = Pembayaran::with('peserta.user','kwitansi')
            ->whereHas('peserta', fn ($p) => $p->where('jenis','kursus'));

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('peserta.user', fn ($u) =>
                $u->where('name','like',"%{$q}%")->orWhere('email','like',"%{$q}%")
            );
        }

        $totalNominal = (clone $query)->sum('nominal');
        $pembayarans = $query->orderByDesc('tanggal')->paginate(20);

        return view('admin.pembayaran.index', compact('pembayarans','totalNominal'));
    }

    public function indexPkl(Request $request)
    {
        $query = Pembayaran::with('peserta.user','kwitansi')
            ->whereHas('peserta', fn ($p) => $p->where('jenis','pkl'));

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('peserta.user', fn ($u) =>
                $u->where('name','like',"%{$q}%")->orWhere('email','like',"%{$q}%")
            );
        }

        $totalNominal = (clone $query)->sum('nominal');
        $pembayarans = $query->orderByDesc('tanggal')->paginate(20);

        return view('admin.pembayaran.index', compact('pembayarans','totalNominal'));
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        $pesertas = Peserta::with('user')
            ->where('status','!=','pending')
            ->orderByDesc('created_at')
            ->get();

        if ($pesertas->isEmpty()) {
            return back()->with('error','Belum ada peserta yang aktif.');
        }

        return view('admin.pembayaran.create', compact('pesertas'));
    }

    // =========================
    // STORE (SELALU PENDING)
    // =========================
    public function store(Request $request)
    {
        $data = $request->validate([
            'peserta_id' => 'required|exists:pesertas,id',
            'nominal'    => 'required|integer|min:0',
            'metode'     => 'required|in:tunai,transfer',
            'tanggal'    => 'required|date',
        ]);

        $peserta = Peserta::findOrFail($data['peserta_id']);

        if ($peserta->jenis === 'kursus' && !$peserta->isActive()) {
            return back()->withErrors(['peserta_id'=>'Peserta sudah keluar.'])->withInput();
        }

        if ($peserta->tanggal_masuk && Carbon::parse($data['tanggal'])->lt($peserta->tanggal_masuk)) {
            return back()->withErrors(['tanggal'=>'Tanggal sebelum tanggal masuk.'])->withInput();
        }

        if ($request->hasFile('bukti')) {
            $data['bukti_path'] = $request->file('bukti')->store('bukti','public');
        }

        $data['status'] = 'pending';

        Pembayaran::create($data);

        return redirect()->route('admin.pembayaran.index')
            ->with('success','Pembayaran masuk, menunggu konfirmasi admin.');
    }

    // =========================
    // ACCEPT PEMBAYARAN
    // =========================
    public function accept(Pembayaran $pembayaran)
    {
        if ($pembayaran->status === 'diterima') {
            return back()->with('info','Pembayaran sudah diterima.');
        }

        $pembayaran->update(['status' => 'diterima']);

        // AUTO KWITANSI (PKL & KURSUS)
        if (!$pembayaran->kwitansi) {
            $this->generateKwitansi($pembayaran);
        }

        return back()->with('success','Pembayaran diterima & kwitansi dibuat.');
    }

    // =========================
    // DELETE
    // =========================
    public function destroy(Pembayaran $pembayaran)
    {
        if ($pembayaran->bukti_path) {
            Storage::disk('public')->delete($pembayaran->bukti_path);
        }

        if ($pembayaran->kwitansi) {
            Storage::delete($pembayaran->kwitansi->file_pdf);
            $pembayaran->kwitansi->delete();
        }

        $pembayaran->delete();

        return back()->with('success','Pembayaran berhasil dihapus.');
    }

    // =========================
    // AUTO GENERATE KWITANSI
    // =========================
    protected function generateKwitansi(Pembayaran $pembayaran)
    {
        $nomor = 'KW-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        $pdf = Pdf::loadView('admin.kwitansi.pdf', compact('pembayaran','nomor'))
            ->setPaper('a4','landscape');

        $path = "kwitansi/kw_{$pembayaran->id}_" . time() . ".pdf";

        Storage::put($path, $pdf->output());

        Kwitansi::create([
            'pembayaran_id' => $pembayaran->id,
            'nomor_kwitansi'=> $nomor,
            'file_pdf'      => $path
        ]);
    }
}

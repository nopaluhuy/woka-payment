<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\Pembayaran;
use App\Models\User;
use App\Models\Kwitansi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PesertaController extends Controller
{
    public function kursus()
    {
        $user = auth()->user();
        $pesertas = Peserta::where('user_id', $user->id)
            ->where('jenis', 'kursus')
            ->get();

        return view('peserta.kursus.index', compact('pesertas'));
    }

    public function index()
    {
        $user = auth()->user();
        $peserta = Peserta::where('user_id', $user->id)
            ->with('pembayaran')
            ->first();

        if (!$peserta) {
            return view('peserta.index')->with('peserta', null);
        }

        return view('peserta.index', compact('peserta'));
    }

    public function pkl()
    {
        $user = auth()->user();
        $pesertas = Peserta::where('user_id', $user->id)
            ->where('jenis', 'pkl')
            ->get();

        return view('peserta.pkl.index', compact('pesertas'));
    }

    public function pembayaran()
    {
        $user = auth()->user();
        $peserta = Peserta::where('user_id', $user->id)->firstOrFail();
        $peserta->load('pembayaran');

        return view('peserta.pembayaran.index', compact('peserta'));
    }

    public function storePembayaran(Request $request, Peserta $peserta)
    {
        if ($peserta->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'nominal' => 'required|integer|min:1',
            'metode' => 'required|in:tunai,transfer',
            'bukti' => 'nullable|image|max:2048'
        ]);

        $payload = [
            'peserta_id' => $peserta->id,
            'nominal' => $data['nominal'],
            'metode' => $data['metode'],
            'tanggal' => Carbon::now()->toDateString(),
            'status' => 'pending'
        ];

        if ($request->hasFile('bukti') && $request->file('bukti')->isValid()) {
            $payload['bukti_path'] = $request->file('bukti')->store('bukti', 'public');
        }

        Pembayaran::create($payload);

        return back()->with('success', 'Pembayaran diajukan. Menunggu konfirmasi admin.');
    }

    public function profile()
    {
        $user = auth()->user();
        $peserta = Peserta::where('user_id', $user->id)->first();

        return view('peserta.profile', compact('user', 'peserta'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_wa' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'no_wa' => $data['no_wa'] ?? null,
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
            $user->save();
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function downloadKwitansi(Kwitansi $kwitansi)
    {
        $user = auth()->user();
        $pembayaran = $kwitansi->pembayaran;

        if (!$pembayaran || $pembayaran->peserta->user_id !== $user->id) {
            abort(403);
        }

        if (!$kwitansi->file_pdf || !Storage::exists($kwitansi->file_pdf)) {
            abort(404);
        }

        return Storage::download(
            $kwitansi->file_pdf,
            ($kwitansi->nomor_kwitansi ?? 'kwitansi') . '.pdf'
        );
    }

    public function showKursusRegistrationForm()
    {
        $jadwals = [
            ['label' => 'Senin, Kamis (14:00 - 17:00)'],
            ['label' => 'Selasa, Jumat (14:30 - 17:30)'],
            ['label' => 'Rabu, Sabtu (13:00 - 16:00)'],
            ['label' => 'Sabtu, Minggu (09:00 - 12:00)'],
        ];

        return view('auth.register_kursus', compact('jadwals'));
    }

    // ✅ FIX JSON DI SINI
    public function storeKursusRegistration(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'program' => 'required|string',
            'jadwal' => 'required|array|min:1',
            'jadwal.*' => 'string',
            'no_wa' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'peserta',
        ]);

        Peserta::create([
            'user_id' => $user->id,
            'jenis' => 'kursus',
            'program' => $data['program'],
            'jadwal' => $data['jadwal'], // ✅ ARRAY JSON MURNI
            'no_wa' => $data['no_wa'],
            'tanggal_masuk' => now(),
            'biaya_per_bulan' => 0,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Anda berhasil mendaftar, Akun Anda masih menunggu persetujuan admin.');
    }

    public function keluar(Request $request)
    {
        $request->validate([
            'alasan_keluar' => 'required|string|max:255',
        ]);

        $peserta = auth()->user()->peserta;

        if (!$peserta || $peserta->jenis !== 'kursus') {
            abort(403);
        }

        // ⛔ CEK PEMBAYARAN PENDING (TARUH DI SINI)
        if ($peserta->pembayaran()->where('status', 'pending')->exists()) {
            return back()->withErrors([
                'alasan_keluar' => 'Tidak bisa keluar kursus karena masih ada pembayaran pending.'
            ]);
        }

        // ✅ UPDATE DATA
        $peserta->update([
            'tanggal_keluar' => now(),
            'status' => 'nonaktif',
            'alasan_keluar' => $request->alasan_keluar,
        ]);

        auth()->logout();

        return redirect('/')
            ->with('success', 'Anda telah keluar dari kursus.');
    }


}

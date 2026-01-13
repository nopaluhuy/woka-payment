<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PesertaController extends Controller
{
    // PESERTA KURSUS SAJA
    public function kursus(Request $request)
    {
        $query = Peserta::with('user')
            ->where('jenis', 'kursus')
            ->where('status', 'diterima');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('user', fn ($b) =>
                $b->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
            );
        }

        $pesertas = $query->latest()->paginate(20)->withQueryString();

        return view('admin.peserta.index', compact('pesertas'))
            ->with('context', 'kursus');
    }

    // PESERTA PKL SAJA
    public function pkl(Request $request)
    {
        $query = Peserta::with('user')->where('jenis', 'pkl');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('user', fn ($b) =>
                $b->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
            );
        }

        $pesertas = $query->latest()->paginate(20)->withQueryString();

        return view('admin.peserta.index', compact('pesertas'))
            ->with('context', 'pkl');
    }

    // INDEX SEMUA PESERTA
    public function index(Request $request)
    {
        $query = Peserta::with('user')->where('status', 'diterima');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('user', fn ($b) =>
                $b->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%")
            );
        }

        $pesertas = $query->latest()->paginate(20)->withQueryString();

        return view('admin.peserta.index', compact('pesertas'))
            ->with('context', 'all');
    }

    // =========================
    // CREATE
    // =========================
    public function create(string $jenis)
    {
        abort_unless(in_array($jenis, ['kursus', 'pkl']), 404);
        return view('admin.peserta.create', compact('jenis'));
    }

    // =========================
    // STORE (FIX JSON)
    // =========================
    public function store(Request $request)
    {
        $request->merge([
            'biaya_per_bulan' => preg_replace('/\D/', '', $request->biaya_per_bulan),
        ]);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'jenis' => 'required|in:kursus,pkl',
            'program' => 'required|string',
            'jadwal' => 'nullable|array', // ✅ FIX
            'no_wa' => 'nullable|string',
            'tanggal_masuk' => 'required|date',
            'biaya_per_bulan' => 'required|numeric|min:0',
        ];

        if ($request->jenis === 'pkl') {
            $rules['asal_sekolah'] = 'required|string';
            $rules['durasi_bulan'] = 'required|integer|min:1';
        }

        $data = $request->validate($rules);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'peserta',
        ]);

        $isAdmin = auth()->check() && auth()->user()->role === 'admin';
        $status = $isAdmin ? 'diterima' : 'pending';

        // ✅ FIX: jadwal selalu ARRAY
        if ($data['jenis'] === 'pkl') {
            $jadwal = $data['jadwal'] ?? ['Senin-Jumat (08:30 - 15:30)'];
        } else {
            $jadwal = $data['jadwal'] ?? [];
        }

        Peserta::create([
            'user_id' => $user->id,
            'jenis' => $data['jenis'],
            'program' => $data['program'],
            'jadwal' => $jadwal, // ✅ ARRAY
            'no_wa' => $data['no_wa'] ?? null,
            'asal_sekolah' => $data['jenis'] === 'pkl' ? $data['asal_sekolah'] : null,
            'durasi_bulan' => $data['jenis'] === 'pkl' ? $data['durasi_bulan'] : null,
            'biaya_per_bulan' => $data['biaya_per_bulan'],
            'tanggal_masuk' => $data['tanggal_masuk'],
            'status' => $status,
        ]);

        return redirect()->route('admin.peserta.index')
            ->with('success', 'Peserta berhasil ditambahkan');
    }

    // =========================
    // PENDAFTARAN
    // =========================
    public function indexPendaftaran()
    {
        $pesertas = Peserta::with('user')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.peserta.index', compact('pesertas'));
    }

    // =========================
    // KONFIRMASI (FIX JSON)
    // =========================
    public function confirm(Peserta $peserta)
    {
        return view('admin.peserta.confirm', compact('peserta'));
    }

    public function accept(Request $request, Peserta $peserta)
    {
        $request->validate([
            'jadwal' => 'required|array', // ✅ FIX
        ]);

        $peserta->update([
            'jadwal' => $request->jadwal, // ✅ ARRAY
            'status' => 'diterima',
        ]);

        return redirect()->route('admin.peserta.index')
            ->with('success', 'Peserta diterima');
    }

    // =========================
    // EDIT / UPDATE (FIX JSON)
    // =========================
    public function edit(Peserta $peserta)
    {
        $users = User::all();
        return view('admin.peserta.edit', compact('peserta', 'users'));
    }

    public function update(Request $request, Peserta $peserta)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jenis' => 'required|in:kursus,pkl',
            'program' => 'nullable|string|max:255',
            'asal_sekolah' => 'nullable|string|max:255',
            'jadwal' => 'nullable|array', // ✅ FIX
            'biaya_per_bulan' => 'nullable|string',
            'durasi_bulan' => 'nullable|integer',
            'tanggal_masuk' => 'nullable|date',
        ]);

        if (!empty($data['biaya_per_bulan'])) {
            $data['biaya_per_bulan'] = (int) preg_replace('/\D/', '', $data['biaya_per_bulan']);
        }

        if (empty($data['tanggal_masuk'])) {
            $data['tanggal_masuk'] = null;
        }

        if ($data['jenis'] !== 'pkl') {
            $data['asal_sekolah'] = null;
            $data['durasi_bulan'] = null;
        }

        $peserta->update($data);

        return redirect()->route('admin.peserta.index')
            ->with('success', 'Peserta berhasil diperbarui.');
    }

    public function destroy(Peserta $peserta)
    {
        $peserta->user?->delete();
        $peserta->delete();
        return back();
    }
}

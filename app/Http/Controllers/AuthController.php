<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        /* =========================
         | VALIDASI INPUT
         ========================= */
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        /* =========================
         | CEK USER
         ========================= */
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ]);
        }

        /* =========================
         | AUTHENTIKASI
         ========================= */
        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        /* =========================
         | LOGIC PESERTA
         ========================= */
        if ($user->role === 'peserta') {
            $peserta = $user->peserta;

            if (!$peserta) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Data peserta tidak ditemukan.',
                ]);
            }

            // update status otomatis (kursus telat bayar)
            $peserta->refreshStatus();

            /* =========================
             | STATUS PENDING
             ========================= */
            if ($peserta->status === 'pending') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda masih menunggu persetujuan admin.',
                ]);
            }

            /* =========================
 | STATUS NONAKTIF
 ========================= */
            if ($peserta->status === 'nonaktif') {
                Auth::logout();

                $wa = '6285383519968';

                // 🚪 KELUAR KURSUS
                if (!empty($peserta->alasan_keluar)) {
                    $text = urlencode(
                        'Halo Admin, saya sebelumnya keluar dari kursus. '
                        . 'Saya ingin melakukan konfirmasi / bertanya terkait pendaftaran kembali.'
                    );

                    return back()->withErrors([
                        'email' => 'Anda sudah keluar dari kursus.
            Jika ingin konfirmasi atau mendaftar ulang, silakan hubungi admin:
            <a href="https://wa.me/' . $wa . '?text=' . $text . '"
               target="_blank"
               class="text-success fw-bold">
               klik di sini💬
            </a>',
                    ]);
                }

                // ⏰ TELAT PEMBAYARAN
                $text = urlencode(
                    'Halo Admin, akun saya nonaktif karena telat pembayaran. '
                    . 'Saya ingin mengaktifkan kembali akun saya.'
                );

                return back()->withErrors([
                    'email' => 'Akun Anda nonaktif karena telat pembayaran.
        Silakan hubungi admin:
        <a href="https://wa.me/' . $wa . '?text=' . $text . '"
           target="_blank"
           class="text-success fw-bold">
           klik di sini💬
        </a>',
                ]);
            }




            /* =========================
             | CEK AKTIF (PAYMENT)
             ========================= */
            if (!$peserta->isActive()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda belum aktif. Silakan lakukan pembayaran.',
                ]);
            }
        }

        /* =========================
         | REDIRECT
         ========================= */
        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('peserta.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

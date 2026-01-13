<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePesertaActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // hanya peserta
        if ($user->role !== 'peserta') {
            abort(403, 'Unauthorized');
        }

        $peserta = $user->peserta;

        if (!$peserta || !$peserta->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Akun Anda nonaktif karena telat pembayaran.',
                ]);
        }

        return $next($request);
    }
}

<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class MenuHelper
{
    /**
     * Mendapatkan menu utama untuk peserta
     *
     * @return array
     */
    public static function getMenuPeserta()
    {
        // Default items untuk peserta (Dashboard + Pembayaran)
        $items = [
            ['name' => 'Dashboard', 'icon' => 'dashboard', 'path' => '/admin/dashboard',],
            ['name' => 'Pendaftaran', 'icon' => 'clipboard-list', 'path' => '/admin/pendaftaran',],
            ['name' => 'Peserta', 'icon' => 'users', 'subItems' => [['name' => 'Semua Peserta', 'path' => '/admin/peserta',], ['name' => 'Peserta Kursus', 'path' => '/admin/peserta/kursus',], ['name' => 'Peserta PKL', 'path' => '/admin/peserta/pkl',],],],
            ['name' => 'Pembayaran', 'icon' => 'credit-card', 'path' => '/admin/pembayaran',],
            ['name' => 'Kwitansi', 'icon' => 'file-text', 'path' => '/admin/kwitansi',],
            ['name' => 'Laporan', 'icon' => 'bar-chart', 'path' => '/admin/laporan',],
        ];

        try {
            $user = auth()->user();
            if ($user && isset($user->peserta)) {
                $jenis = strtolower($user->peserta->jenis ?? '');

                if ($jenis === 'kursus') {
                    $items = [
                        [
                            'name' => 'Beranda',
                            'path' => Route::has('peserta.index') ? route('peserta.index') : '/peserta',
                            'icon' => 'dashboard',
                        ],
                        [
                            'name' => 'Kursus Saya',
                            'path' => Route::has('peserta.kursus.saya')
                                ? route('peserta.kursus.saya')
                                : '/peserta/kursus/saya',
                            'icon' => 'book',
                        ],
                    ];
                } elseif ($jenis === 'pkl') {
                    $items = [
                        [
                            'name' => 'Beranda',
                            'path' => Route::has('peserta.index') ? route('peserta.index') : '/peserta',
                            'icon' => 'dashboard',
                        ],
                        [
                            'name' => 'PKL Saya',
                            'path' => Route::has('peserta.pkl.saya')
                                ? route('peserta.pkl.saya')
                                : '/peserta/pkl/saya',
                            'icon' => 'briefcase',
                        ],
                    ];
                }

            }
        } catch (\Throwable $e) {
            // Jika terjadi error auth, tetap pakai default items
        }

        return [
            [
                'title' => 'Menu Peserta',
                'items' => $items,
            ],
        ];
    }

    /**
     * Cek apakah menu aktif
     *
     * @param string $path
     * @return bool
     */
    public static function isActive($path)
    {
        $urlPath = parse_url($path, PHP_URL_PATH) ?: $path;
        return request()->is(ltrim($urlPath, '/'));
    }

    /**
     * Ambil icon SVG
     *
     * @param string $iconName
     * @return string
     */
    public static function getIconSvg($iconName)
    {
        $icons = [
            'dashboard' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h7v7H3V3zM14 3h7v7h-7V3zM3 14h7v7H3v-7zM14 14h7v7h-7v-7z" /> </svg>',
            'book' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9M12 4h9M3 6h9M3 18h9" /> </svg>',
            'briefcase' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 7h20v14H2V7zm5-4h10v4H7V3z" /> </svg>',
            'credit-card' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 7h20v14H2V7zm2 4h4v2H4v-2z" /> </svg>',
            'file-text' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/></svg>',
            'bar-chart' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 6v14M10 10v10M16 4v16"/></svg>',
            'user' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M16 14a4 4 0 10-8 0m8 0a4 4 0 01-8 0m8 0v6H8v-6"/></svg>',
            'clipboard-list' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 3h6a2 2 0 012 2v2H7V5a2 2 0 012-2zm0 8h6m-6 4h6"/></svg>',
            'users' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 14a4 4 0 100-8 4 4 0 000 8zM6 20a6 6 0 0112 0H6z"/></svg>',

        ];

        return $icons[$iconName] ?? '<svg class="h-5 w-5"></svg>';
    }
}

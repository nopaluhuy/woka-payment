@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Pembayaran Saya
            </h3>
        </div>

        {{-- Success Alert --}}
        @if(session('success'))
            <div id="success-alert" class="fixed top-[15%] left-1/2 
                           bg-green-50 border border-green-200 text-green-800 
                           px-6 py-3 rounded-full shadow-md flex items-center gap-2
                           transition-all duration-500 opacity-100 z-50" style="transform: translateX(-50%);">
                <!-- Icon centang -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>

            <script>
                setTimeout(function () {
                    const alert = document.getElementById('success-alert');
                    alert.style.opacity = '0';
                    // Tetap center horizontal, naik lurus ke atas
                    alert.style.transform = 'translateX(-50%) translateY(-50px)';
                    setTimeout(() => alert.remove(), 500);
                }, 2000);
            </script>
        @endif

        {{-- Biodata --}}
        <div
            class="mb-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="mb-3 text-base font-semibold text-gray-800 dark:text-white/90">
                Biodata
            </h4>

            <div class="space-y-1 text-sm text-gray-700 dark:text-gray-400">
                <p><strong>Nama:</strong> {{ optional($peserta->user)->name }}</p>
                <p><strong>Jenis:</strong> {{ $peserta->jenis }}</p>
                <p><strong>Program:</strong> {{ $peserta->program ?? '-' }}</p>
            </div>
        </div>

        {{-- Status Pembayaran --}}
        <div
            class="mb-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="mb-3 text-base font-semibold text-gray-800 dark:text-white/90">
                Status Pembayaran
            </h4>

            @php
                $sisa = $peserta->sisaPembayaran();
                $status = 'belum_bayar';
                if ($sisa === 0)
                    $status = 'lunas';
                elseif ($peserta->totalDibayar() > 0 && $sisa > 0)
                    $status = 'cicilan';
            @endphp

            <div class="space-y-1 text-sm text-gray-700 dark:text-gray-400">
                <p>
                    <strong>Status:</strong>
                    @if($status === 'belum_bayar')
                        <span class="text-red-600">Belum Bayar</span>
                    @elseif($status === 'cicilan')
                        <span class="text-yellow-600">Cicilan</span>
                    @else
                        <span class="text-green-600">Lunas</span>
                    @endif
                </p>
                <p>
                    <strong>Sisa:</strong>
                    {{ $sisa !== null ? number_format($sisa, 0, ',', '.') : '-' }}
                </p>
            </div>
        </div>

        {{-- Ajukan Pembayaran --}}
        <div
            class="mb-8 rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="mb-4 text-base font-semibold text-gray-800 dark:text-white/90">
                Ajukan Pembayaran
            </h4>

            <form action="{{ route('peserta.pembayaran.store', $peserta) }}" method="post" enctype="multipart/form-data"
                class="space-y-4">
                @csrf

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nominal
                    </label>
                    <input name="nominal" required class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800
                               shadow-theme-xs focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10
                               focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Metode
                    </label>
                    <select name="metode" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800
                               shadow-theme-xs focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10
                               focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="tunai">Tunai (konfirmasi admin)</option>
                        <option value="transfer">Transfer (unggah bukti)</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Bukti Transfer (jika transfer)
                    </label>
                    <input type="file" name="bukti" accept="image/*" class="h-11 w-full rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500
                               shadow-theme-xs file:mr-4 file:cursor-pointer file:border-0 file:bg-gray-100
                               file:px-4 file:py-2 hover:file:bg-gray-200
                               dark:border-gray-700 dark:bg-gray-900 dark:file:bg-white/[0.05]" />
                </div>

                <button class="inline-flex items-center rounded-lg bg-brand-500 px-6 py-3 text-sm font-medium
                           text-white shadow-theme-xs hover:bg-brand-600">
                    Kirim Pembayaran
                </button>
            </form>
        </div>

        {{-- Riwayat Pembayaran --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">

            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h4 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Riwayat Pembayaran
                </h4>
            </div>

            <div class="overflow-x-auto px-6">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-y border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500">#</th>
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500">Nominal</th>
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500">Metode</th>
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500">Tanggal</th>
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500">Bukti</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($peserta->pembayaran as $p)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                    {{ number_format($p->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $p->metode }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $p->tanggal }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $p->status }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($p->bukti_path)
                                        <a target="_blank" href="{{ asset('storage/' . $p->bukti_path) }}"
                                            class="text-brand-500 hover:underline">
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>
@endsection
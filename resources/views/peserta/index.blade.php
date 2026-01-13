    @extends('layouts.app')

    @section('title', 'Halaman Peserta')

    @section('content')
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Beranda
            </h3>
        </div>

        {{-- Success Alert --}}
        @if(session('success'))
            <div id="success-alert" class="fixed top-[15%] left-1/2 
                bg-green-50 border border-green-200 text-green-800 
                px-6 py-3 rounded-full shadow-md flex items-center gap-2
                transition-all duration-500 opacity-100 z-50" style="transform: translateX(-50%);">
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
                    alert.style.transform = 'translateX(-50%) translateY(-50px)';
                    setTimeout(() => alert.remove(), 500);
                }, 2000);
            </script>
        @endif

        {{-- Empty --}}
        @if(!$peserta)
            <div class="rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">
                Belum ada data peserta terdaftar untuk akun Anda.
            </div>
        @else

            {{-- Peserta Info --}}
            <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                <h4 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    {{ optional($peserta->user)->name }}
                    <span class="text-sm text-gray-500">— {{ strtoupper($peserta->jenis) }}</span>
                </h4>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Program: {{ $peserta->program ?? '-' }}
                </p>
            </div>

            {{-- Tabs --}}
            <div class="mb-6 border-b border-gray-200 dark:border-gray-800">
                <nav class="flex gap-6 text-sm font-medium">
                    <button data-tab="biodata" class="tab-btn border-b-2 border-brand-500 pb-2 text-brand-600">
                        Biodata
                    </button>
                    <button data-tab="status" class="tab-btn border-b-2 border-transparent pb-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        Status
                    </button>
                    <button data-tab="pembayaran" class="tab-btn border-b-2 border-transparent pb-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        Pembayaran
                    </button>
                </nav>
            </div>

            {{-- Tab Content --}}
            <div>

                {{-- Biodata --}}
                <div id="biodata" class="tab-panel">
                    <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                        <table class="min-w-full">
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-normal text-gray-900 dark:text-white/90">Nama</th>
                                    <td class="px-6 py-3 text-sm text-gray-500">{{ optional($peserta->user)->name }}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-normal text-gray-900 dark:text-white/90">Jenis</th>
                                    <td class="px-6 py-3 text-sm text-gray-500">{{ $peserta->jenis }}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-normal text-gray-900 dark:text-white/90">Program / Asal</th>
                                    <td class="px-6 py-3 text-sm text-gray-500">{{ $peserta->program ?? $peserta->asal_sekolah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-normal text-gray-900 dark:text-white/90">Biaya / bulan</th>
                                    <td class="px-6 py-3 text-sm text-gray-500">
                                        {{ $peserta->biaya_per_bulan ? 'Rp ' . number_format($peserta->biaya_per_bulan,0,',','.') : '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-normal text-gray-900 dark:text-white/90">Durasi (bulan)</th>
                                    <td class="px-6 py-3 text-sm text-gray-500">{{ $peserta->durasi_bulan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-normal text-gray-900 dark:text-white/90">Tanggal Masuk</th>
                                    <td class="px-6 py-3 text-sm text-gray-500">{{ $peserta->tanggal_masuk ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Status --}}
                <div id="status" class="tab-panel hidden">
                    @php
                        $totalDibayar = $peserta->totalDibayar() ?? 0;
                        $totalBiaya = $peserta->totalBiaya();
                        $sisa = $peserta->sisaPembayaran();
                        $st = ($totalDibayar == 0) ? 'pending' : (($sisa === 0) ? 'diterima' : 'cicilan');
                    @endphp

                    <div class="flex flex-col lg:flex-row gap-6">
                        <div class="flex-1 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                            <div class="max-w-full overflow-x-auto px-5 py-4 sm:px-6">
                                <table class="min-w-full">
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        <tr>
                                            <td class="px-4 py-4 text-sm font-normal text-gray-900 dark:text-white">Status</td>
                                            <td class="px-4 py-4 text-sm">
                                                @if($st === 'pending')
                                                    <span class="inline-flex rounded-full bg-red-50 px-2 text-xs font-semibold text-red-600 dark:bg-red-500/15 dark:text-orange-400">
                                                        Belum Bayar
                                                    </span>
                                                @elseif($st === 'diterima')
                                                    <span class="inline-flex rounded-full bg-green-50 px-2 text-xs font-semibold text-green-600 dark:bg-green-500/15 dark:text-green-500">
                                                        Lunas
                                                    </span>
                                                @else
                                                    <span class="inline-flex rounded-full bg-blue-50 px-2 text-xs font-semibold text-blue-600 dark:bg-blue-500/15 dark:text-blue-500">
                                                        Cicilan
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="px-4 py-4 text-sm font-normal text-gray-900 dark:text-white">Total Dibayar</td>
                                            <td class="px-4 py-4 text-sm text-gray-500">Rp {{ number_format($totalDibayar,0,',','.') }}</td>
                                        </tr>

                                        <tr>
                                            <td class="px-4 py-4 text-sm font-normal text-gray-900 dark:text-white">Total Biaya</td>
                                            <td class="px-4 py-4 text-sm text-gray-500">
                                                {{ $totalBiaya !== null ? 'Rp ' . number_format($totalBiaya,0,',','.') : '-' }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="px-4 py-4 text-sm font-normal text-gray-900 dark:text-white">Sisa</td>
                                            <td class="px-4 py-4 text-sm text-gray-500">{{ $sisa !== null ? 'Rp ' . number_format($sisa,0,',','.') : '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Kwitansi Panel --}}
                        <div class="w-full lg:w-80 overflow-hidden rounded-2xl border border-gray-200 bg-white p-4 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                            <h4 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white/90">Kwitansi</h4>

                            @php
                                $kwitans = $peserta->pembayaran->pluck('kwitansi')->filter();
                            @endphp

                            @if($kwitans->isEmpty())
                                <div class="text-sm text-gray-500">Belum ada kwitansi tersedia.</div>
                            @else
                                <div class="space-y-3">
                                    @foreach($kwitans as $k)
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="min-w-0">
                                                <div class="truncate text-sm font-medium text-gray-900 dark:text-white/90">{{ $k->nomor_kwitansi ?? 'No. Kwitansi' }}</div>
                                                <div class="text-xs text-gray-500">{{ optional($k->pembayaran)->tanggal ?? '-' }}</div>
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="{{ route('peserta.kwitansi.download', $k) }}"
                                                class="inline-flex items-center rounded-md border border-gray-200 bg-white px-3 py-1 text-xs text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Pembayaran --}}
                <div id="pembayaran" class="tab-panel hidden">
                    {{-- Riwayat Pembayaran --}}
                    <div class="mb-6 rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                            <h4 class="font-semibold text-gray-900 dark:text-white/90">Riwayat Pembayaran</h4>
                        </div>
                        <div class="overflow-x-auto px-6">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-y">
                                        <th class="px-4 py-3 text-sm text-gray-500">Tanggal</th>
                                        <th class="px-4 py-3 text-sm text-gray-500">Nominal</th>
                                        <th class="px-4 py-3 text-sm text-gray-500">Metode</th>
                                        <th class="px-4 py-3 text-sm text-gray-500">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach($peserta->pembayaran as $p)
                                        <tr class="text-center">
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">Rp {{ number_format($p->nominal,0,',','.') }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($p->metode) }}</td>
                                            <td class="px-4 py-3 text-sm">
                                                @if($p->status === 'pending')
                                                    <span class="inline-flex rounded-full bg-yellow-50 px-2 text-xs font-semibold text-yellow-600 dark:bg-yellow-500/15 dark:text-orange-400">Pending</span>
                                                @elseif($p->status === 'diterima')
                                                    <span class="inline-flex rounded-full bg-green-50 px-2 text-xs font-semibold text-green-600 dark:bg-green-500/15 dark:text-green-500">Diterima</span>
                                                @else
                                                    <span class="inline-flex rounded-full bg-blue-50 px-2 text-xs font-semibold text-blue-600 dark:bg-blue-500/15 dark:text-blue-500">Cicilan</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Ajukan Pembayaran --}}
                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                        <h4 class="mb-4 font-semibold text-gray-900 dark:text-white/90">Ajukan Pembayaran</h4>
                        <form action="{{ route('peserta.pembayaran.store', $peserta) }}" method="post" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            {{-- Nominal --}}
                            <div>
                                <input type="hidden" name="nominal" id="nominal" value="{{ old('nominal') ?? '' }}">
                                <input type="text" id="nominal_formatted" required placeholder="Nominal (Rp)"
                                    class="h-11 w-full rounded-lg border border-gray-300 px-4 text-sm focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                    value="{{ old('nominal') ? 'Rp ' . number_format(old('nominal'),0,',','.') : '' }}">
                            </div>

                            {{-- Metode --}}
                            <select name="metode" id="metode" required
                                class="h-11 w-full appearance-none rounded-lg border border-gray-300 px-4 text-sm focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="tunai" {{ old('metode') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="transfer" {{ old('metode') == 'transfer' ? 'selected' : '' }}>Transfer (upload bukti)</option>
                            </select>

                            {{-- Bukti Transfer --}}
                            <div id="bukti-container" class="hidden">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Bukti Transfer (Wajib)</label>
                                <input type="file" name="bukti" id="bukti" accept="image/*"
                                    class="h-11 w-full rounded-lg border border-gray-300 px-4 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                            </div>

                            <button class="rounded-lg bg-brand-500 px-6 py-3 text-sm font-medium text-white hover:bg-brand-600">
                                Kirim Pembayaran
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            {{-- Tabs JS --}}
            <script>
                const tabs = document.querySelectorAll('.tab-btn');
                const panels = document.querySelectorAll('.tab-panel');
                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        tabs.forEach(t => {
                            t.classList.remove('border-brand-500','text-brand-600');
                            t.classList.add('border-transparent','text-gray-500');
                        });
                        panels.forEach(p => p.classList.add('hidden'));
                        tab.classList.add('border-brand-500','text-brand-600');
                        tab.classList.remove('border-transparent','text-gray-500');
                        document.getElementById(tab.dataset.tab).classList.remove('hidden');
                    });
                });
            </script>

            {{-- Format Rp --}}
            <script>
                (function(){
                    const formatted = document.getElementById('nominal_formatted');
                    const hidden = document.getElementById('nominal');
                    if(!formatted || !hidden) return;
                    formatted.addEventListener('input', function(){
                        const digits = this.value.replace(/\D/g,'');
                        hidden.value = digits ? parseInt(digits,10) : '';
                        this.value = digits ? 'Rp ' + digits.replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '';
                    });
                })();
            </script>

            {{-- Toggle Bukti Transfer --}}
            <script>
                (function(){
                    const metode = document.getElementById('metode');
                    const buktiContainer = document.getElementById('bukti-container');
                    const buktiInput = document.getElementById('bukti');
                    if(!metode || !buktiContainer || !buktiInput) return;
                    function toggleBukti() {
                        if(metode.value === 'transfer'){
                            buktiContainer.classList.remove('hidden');
                            buktiInput.required = true;
                        } else {
                            buktiContainer.classList.add('hidden');
                            buktiInput.required = false;
                            buktiInput.value = '';
                        }
                    }
                    toggleBukti();
                    metode.addEventListener('change', toggleBukti);
                })();
            </script>

        @endif
    </div>
    @endsection

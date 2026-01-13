    @extends('layouts.app')

    @section('content')
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

                {{-- Header --}}
                <div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                        Data Pembayaran
                    </h3>
                </div>

                {{-- Alert --}}
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

                {{-- Search & Action --}}
                <div class="flex flex-col gap-3 px-5 mb-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                    {{-- Search --}}
                    <form method="get" class="w-full sm:max-w-md">
                        <div class="relative">
                            <input name="q" value="{{ request('q') }}" placeholder="Cari peserta..."
                                class="h-[42px] w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-4 pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        </div>
                    </form>

                    {{-- Button --}}
                    <a href="{{ route('admin.pembayaran.create') }}"
                        class="inline-flex h-[42px] items-center justify-center rounded-lg bg-brand-500 px-5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Tambah Pembayaran
                    </a>

                </div>

                {{-- Total --}}
                <div class="px-5 mb-4 sm:px-6">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-400">
                        Total Pembayaran:
                        <span class="font-semibold text-gray-900 dark:text-white">
                            Rp {{ number_format($totalNominal ?? 0, 0, ',', '.') }}
                        </span>
                    </p>
                </div>

                {{-- Table --}}
                <div class="overflow-hidden">
                    <div class="max-w-full overflow-x-auto px-5 sm:px-6">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-y border-gray-200 dark:border-gray-700">
                                    <th class="px-4 py-3 text-left text-sm font-normal text-gray-500">#</th>
                                    <th class="px-4 py-3 text-left text-sm font-normal text-gray-500">Peserta</th>
                                    <th class="px-4 py-3 text-left text-sm font-normal text-gray-500">Status</th>
                                    <th class="px-4 py-3 text-left text-sm font-normal text-gray-500">Nominal</th>
                                    <th class="px-4 py-3 text-left text-sm font-normal text-gray-500">Sisa</th>
                                    <th class="px-4 py-3 text-left text-sm font-normal text-gray-500">Metode</th>
                                    <th class="px-4 py-3 text-left text-sm font-normal text-gray-500">Tanggal</th>
                                    <th class="px-4 py-3 text-left text-sm font-normal text-gray-500">Kwitansi</th>
                                    <th class="px-4 py-3 text-right text-sm font-normal text-gray-500">Aksi</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($pembayarans as $p)
                                                    <tr>
                                                        <td class="px-4 py-4 text-sm text-gray-500">
                                                            {{ $loop->iteration + ($pembayarans->currentPage() - 1) * $pembayarans->perPage() }}
                                                        </td>

                                                        <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ optional($p->peserta->user)->name ?? '-' }}
                                                            <span class="text-gray-500">({{ $p->peserta->jenis }})</span>
                                                        </td>

                                                        <td class="px-4 py-4">
                                                            @if($p->status === 'pending')
                                                                <span
                                                                    class="inline-flex rounded-full bg-yellow-50 px-2 text-xs font-semibold text-yellow-600 dark:bg-yellow-500/15 dark:text-orange-400">
                                                                    Pending
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="inline-flex rounded-full bg-green-50 px-2 text-xs font-semibold text-green-600 dark:bg-green-500/15 dark:text-green-500">
                                                                    Diterima
                                                                </span>
                                                            @endif
                                                        </td>

                                                        <td class="px-4 py-4 text-sm text-gray-500">
                                                            {{ number_format($p->nominal, 0, ',', '.') }}
                                                        </td>

                                                        <td class="px-4 py-4 text-sm text-gray-500">
                                                            {{ !is_null($p->peserta->sisaPembayaran())
                                    ? number_format($p->peserta->sisaPembayaran(), 0, ',', '.')
                                    : '-' }}
                                                        </td>

                                                        <td class="px-4 py-4 text-sm text-gray-500">
                                                            {{ $p->metode }}
                                                        </td>

                                                        <td class="px-4 py-4 text-sm text-gray-500">
                                                            {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}
                                                        </td>

                                                        <td class="px-4 py-4 text-sm">
                                                            @if(optional($p->kwitansi)->file_pdf)
                                                                <a target="_blank" href="{{ route('admin.kwitansi.download', $p->kwitansi) }}"
                                                                    class="text-blue-500 hover:underline">
                                                                    Unduh
                                                                </a>
                                                            @else
                                                                <span class="text-gray-400">-</span>
                                                            @endif
                                                        </td>

                                                        <td class="px-4 py-4 text-right">
                                                            <div class="inline-flex items-center gap-2">

                                                                @if($p->status === 'pending')
                                                                    <form action="{{ route('admin.pembayaran.accept', $p) }}" method="post">
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="inline-flex items-center rounded-lg bg-green-500 px-3 py-1.5 text-xs font-medium text-white shadow-theme-xs hover:bg-green-600">
                                                                            Terima
                                                                        </button>
                                                                    </form>
                                                                @endif

                                                                <a href="{{ route('admin.pembayaran.edit', $p) }}"
                                                                    class="inline-flex items-center rounded-lg bg-yellow-500 px-3 py-1.5 text-xs font-medium text-white shadow-theme-xs hover:bg-yellow-600">
                                                                    Edit
                                                                </a>

                                                                <form action="{{ route('admin.pembayaran.destroy', $p) }}" method="post"
                                                                    onsubmit="return confirm('Hapus pembayaran?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="inline-flex items-center rounded-lg bg-red-500 px-3 py-1.5 text-xs font-medium text-white shadow-theme-xs hover:bg-red-600">
                                                                        Hapus
                                                                    </button>
                                                                </form>

                                                            </div>
                                                        </td>
                                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="border-t border-gray-200 px-6 py-4 dark:border-white/[0.05]">
                    {{ $pembayarans->links() }}
                </div>

            </div>
        </div>
    @endsection
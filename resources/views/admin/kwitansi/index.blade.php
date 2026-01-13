@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Kwitansi
            </h3>

            <a href="{{ route('admin.kwitansi.create') }}"
                class="inline-flex items-center rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                Buat Kwitansi
            </a>
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

        {{-- Card --}}
        <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

            {{-- Table --}}
            <div class="overflow-hidden">
                <div class="max-w-full overflow-x-auto px-5 sm:px-6">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-y border-gray-200 dark:border-gray-700">
                                <th class="px-4 py-3 text-left font-normal text-gray-500 dark:text-gray-400">#</th>
                                <th class="px-4 py-3 text-left font-normal text-gray-500 dark:text-gray-400">Nomor</th>
                                <th class="px-4 py-3 text-left font-normal text-gray-500 dark:text-gray-400">Pembayaran</th>
                                <th class="px-4 py-3 text-left font-normal text-gray-500 dark:text-gray-400">Peserta</th>
                                <th class="px-4 py-3 text-left font-normal text-gray-500 dark:text-gray-400">File</th>
                                <th class="px-4 py-3 text-right font-normal text-gray-500 dark:text-gray-400">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($kwitansis as $k)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">

                                    <td class="px-4 py-4 text-gray-500">
                                        {{ $loop->iteration + (($kwitansis->currentPage() - 1) * $kwitansis->perPage()) }}
                                    </td>

                                    <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $k->nomor_kwitansi ?? '-' }}
                                    </td>

                                    <td class="px-4 py-4 text-gray-600 dark:text-gray-400">
                                        {{ number_format($k->pembayaran->nominal ?? 0, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-4 text-gray-600 dark:text-gray-400">
                                        {{ optional($k->pembayaran->peserta->user)->name ?? '-' }}
                                    </td>

                                    {{-- File --}}
                                    <td class="px-4 py-4">
                                        @if($k->file_pdf && Storage::exists($k->file_pdf))
                                            <a href="{{ route('admin.kwitansi.download', $k) }}"
                                                class="inline-flex items-center rounded-lg bg-blue-500 px-3 py-1.5 text-xs font-medium text-white shadow-theme-xs hover:bg-blue-600">
                                                Download
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-4 py-4 text-right">
                                        <div class="inline-flex items-center gap-2">

                                            {{-- Regenerate disabled by policy — only manual upload/download allowed --}}

                                            <form action="{{ route('admin.kwitansi.destroy', $k) }}" method="post"
                                                onsubmit="return confirm('Hapus kwitansi?')">
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
                {{ $kwitansis->links() }}
            </div>

        </div>
    </div>
@endsection
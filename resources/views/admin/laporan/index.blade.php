@extends('layouts.app')

@section('content')

{{-- ================= PRINT STYLE (TIDAK MENGUBAH LAYOUT LAYAR) ================= --}}
<style>
@media print {

    header,
    nav,
    aside,
    footer,
    .print-hidden {
        display: none !important;
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
        color: #000 !important;
    }

    .shadow-theme-xs,
    .rounded-2xl {
        box-shadow: none !important;
        border-radius: 0 !important;
    }

    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    th, td {
        border: 1px solid #000 !important;
        padding: 6px 8px !important;
        font-size: 11px !important;
        color: #000 !important;
    }

    th {
        font-weight: bold !important;
        background: #f2f2f2 !important;
    }

    .screen-only {
        display: none !important;
    }

    .print-total {
        display: block !important;
        margin-top: 12px;
        padding-top: 8px;
        border-top: 2px solid #000;
        text-align: right;
        font-size: 13px;
        font-weight: bold;
    }
}
</style>
{{-- ========================================================================== --}}

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

    {{-- HEADER + TOMBOL PRINT --}}
    <div class="mb-6 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Laporan Keuangan
        </h3>

        <button
            onclick="window.print()"
            class="print-hidden rounded-lg bg-black px-4 py-2
                   text-sm font-medium text-white hover:bg-gray-800 dark:bg-white/10">
            Print
        </button>
    </div>

    {{-- TOTAL PEMBAYARAN (LAYAR SAJA) --}}
    <div
        class="mb-6 rounded-2xl border border-gray-200 bg-white p-6
               shadow-theme-xs dark:border-gray-800
               dark:bg-white/[0.03] screen-only">

        <div class="flex items-center gap-3">
            <div
                class="flex h-12 w-12 items-center justify-center
                       rounded-xl bg-green-50 text-green-600
                       dark:bg-green-500/15 dark:text-green-400">
                <svg width="24" height="24" fill="none" stroke="currentColor"
                     stroke-width="1.8" viewBox="0 0 24 24"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 1v22" />
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6" />
                </svg>
            </div>

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Total Pembayaran
                </p>
                <p class="mt-1 text-2xl font-semibold
                          text-gray-900 dark:text-white">
                    Rp {{ number_format($totalAll ?? 0, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>

    {{-- PEMBAYARAN TERBARU --}}
    <div class="rounded-2xl border border-gray-200 bg-white
                shadow-theme-xs dark:border-gray-800
                dark:bg-white/[0.03]">

        <div class="border-b border-gray-200 px-6 py-4
                    dark:border-gray-700">
            <h4 class="text-base font-semibold
                       text-gray-800 dark:text-white/90">
                Pembayaran Terbaru
            </h4>
        </div>

        <div class="overflow-hidden">
            <div class="max-w-full overflow-x-auto px-6">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-y border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500 dark:text-gray-400">#</th>
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500 dark:text-gray-400">Peserta</th>
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500 dark:text-gray-400">Jenis</th>
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500 dark:text-gray-400">Nominal</th>
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500 dark:text-gray-400">Tanggal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($recent as $i => $p)
                            <tr>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $i + 1 }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900 dark:text-white">
                                    {{ optional($p->peserta->user)->name ?? '-' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ ucfirst($p->peserta->jenis ?? '-') }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ number_format($p->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $p->tanggal }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- TOTAL KHUSUS PRINT --}}
                <div class="print-total" style="display:none">
                    Total Pembayaran : Rp {{ number_format($totalAll ?? 0, 0, ',', '.') }}
                </div>

            </div>
        </div>

    </div>

</div>
@endsection

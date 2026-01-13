@extends('layouts.app')

@section('title','PKL Saya')

@section('content')
<div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            PKL Saya
        </h3>
    </div>

    {{-- Empty State --}}
    @if($pesertas->isEmpty())
        <div class="rounded-lg bg-yellow-50 px-4 py-3 text-sm text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400">
            Anda belum terdaftar PKL.
        </div>
    @else

        {{-- List --}}
        <div class="space-y-4">
            @foreach($pesertas as $p)
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="max-w-full overflow-x-auto px-5 py-4 sm:px-6">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-y border-gray-200 dark:border-gray-700">
                                    <th class="px-4 py-3 text-left text-sm text-gray-500">Keterangan</th>
                                    <th class="px-4 py-3 text-left text-sm text-gray-500">Detail</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr>
                                    <td class="px-4 py-4 text-sm font-normal text-gray-900 dark:text-white">Program</td>
                                    <td class="px-4 py-4 text-sm text-gray-500">{{ $p->program }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-4 text-sm font-normal text-gray-900 dark:text-white">Asal Sekolah</td>
                                    <td class="px-4 py-4 text-sm text-gray-500">{{ $p->asal_sekolah ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-4 text-sm font-normal text-gray-900 dark:text-white">Jadwal</td>
                                    <td class="px-4 py-4 text-sm text-gray-500">{{ $p->jadwal_display }}</td>
                                </tr>
                                <tr>
                                    <td class="px-4 py-4 text-sm font-normal text-gray-900 dark:text-white">Tanggal Masuk</td>
                                    <td class="px-4 py-4 text-sm text-gray-500">{{ $p->tanggal_masuk }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

    @endif
</div>
@endsection

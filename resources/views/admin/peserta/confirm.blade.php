@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

    <div class="rounded-2xl border border-gray-200 bg-white p-6
                dark:border-gray-800 dark:bg-white/[0.03]">

        <h3 class="mb-6 text-lg font-semibold text-gray-800 dark:text-white/90">
            Konfirmasi Peserta {{ strtoupper($peserta->jenis) }}
        </h3>

        <div class="space-y-3 text-sm text-gray-700 dark:text-gray-300">
            <div><strong>Nama:</strong> {{ $peserta->user->name }}</div>
            <div><strong>Email:</strong> {{ $peserta->user->email }}</div>
            <div><strong>Jenis:</strong> {{ strtoupper($peserta->jenis) }}</div>
            <div><strong>Program:</strong> {{ $peserta->program }}</div>
            <div>
                <strong>Jadwal Permintaan:</strong>
                <span class="italic">
                    {{ $peserta->jadwal_display }}
                </span>
            </div>
        </div>

        {{-- FORM KONFIRMASI --}}
        <form action="{{ route('admin.peserta.accept', $peserta) }}" method="POST" class="mt-6 space-y-5">
            @csrf

            {{-- JADWAL FINAL --}}
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Jadwal Final
                </label>

                <input
                    name="jadwal[]"
                    value="{{ is_array($peserta->jadwal) ? $peserta->jadwal[0] : $peserta->jadwal }}"
                    required
                    class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm
                           text-gray-800 focus:border-brand-300 focus:ring-3
                           focus:ring-brand-500/10 focus:outline-none
                           dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
            </div>

            {{-- ACTION --}}
            <div class="flex gap-3 pt-4">
                <button type="submit" class="rounded-lg bg-green-500 px-6 py-2.5
                               text-sm font-medium text-white hover:bg-green-600">
                    Terima Peserta
                </button>

                <a href="{{ route('admin.peserta.index') }}"
                   class="rounded-lg border border-gray-300 px-6 py-2.5
                          text-sm font-medium text-gray-700 hover:bg-gray-50
                          dark:border-gray-700 dark:text-gray-300">
                    Kembali
                </a>
            </div>
        </form>

    </div>
</div>
@endsection

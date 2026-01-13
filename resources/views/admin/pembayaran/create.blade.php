@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

        <x-common.component-card title="Tambah Pembayaran">

            {{-- Error --}}
            @if($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-600 dark:bg-red-500/15 dark:text-red-400">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.pembayaran.store') }}" method="post" class="space-y-5">
                @csrf

                {{-- Peserta --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Peserta
                    </label>
                    <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                        <select name="peserta_id" required
                            class="shadow-theme-xs h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                            :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                            @change="isOptionSelected = true">
                            <option value="">Pilih peserta</option>
                            @foreach($pesertas as $ps)
                                <option value="{{ $ps->id }}">
                                    {{ optional($ps->user)->name ?? '-' }} — {{ $ps->jenis }}
                                </option>
                            @endforeach
                        </select>

                        <span class="pointer-events-none absolute top-1/2 right-4 -translate-y-1/2 text-gray-500">
                            <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    </div>
                </div>

                {{-- Grid --}}
                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">


                    {{-- Nominal --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nominal
                        </label>

                        <div class="mt-2 flex">
                            <span class="inline-flex items-center rounded-l-lg border border-r-0 border-gray-300 px-3 text-sm text-gray-600
                             dark:border-gray-700 dark:text-gray-300">
                                Rp
                            </span>

                            <input name="nominal" id="nominal" type="text" required inputmode="numeric" class="w-full rounded-r-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800
                           focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700
                           dark:bg-gray-900 dark:text-white/90" />
                        </div>
                    </div>


                    {{-- Metode --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Metode
                        </label>
                        <div x-data="{ isOptionSelected: false }" class="relative z-20 bg-transparent">
                            <select name="metode" required
                                class="shadow-theme-xs h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                :class="isOptionSelected && 'text-gray-800 dark:text-white/90'"
                                @change="isOptionSelected = true">
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer</option>
                            </select>

                            <span class="pointer-events-none absolute top-1/2 right-4 -translate-y-1/2 text-gray-500">
                                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Tanggal
                        </label>
                        <input name="tanggal" type="date" required
                            class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                    </div>

                </div>

                {{-- Action --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                        class="inline-flex items-center rounded-lg bg-brand-500 px-6 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                        Simpan
                    </button>

                    <a href="{{ route('admin.pembayaran.index') }}"
                        class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        Batal
                    </a>
                </div>

            </form>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const nominal = document.getElementById('nominal');
                    const form = nominal.closest('form');

                    function formatRupiah(angka) {
                        return angka.replace(/\D/g, '')
                            .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    }

                    // Realtime format
                    nominal.addEventListener('keyup', function () {
                        this.value = formatRupiah(this.value);
                    });

                    // Bersihkan format saat submit
                    form.addEventListener('submit', function () {
                        nominal.value = nominal.value.replace(/\D/g, '');
                    });
                });
            </script>


        </x-common.component-card>

    </div>
@endsection
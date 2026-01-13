@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

        {{-- Card --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">

            {{-- Header --}}
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Buat Kwitansi
                </h3>
            </div>

            {{-- Body --}}
            <div class="px-6 py-6">

                {{-- Error --}}
                @if($errors->any())
                    <div class="mb-6 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-600 dark:bg-red-500/15 dark:text-red-400">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('admin.kwitansi.store') }}" method="post" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    {{-- Pembayaran --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Pembayaran
                        </label>

                        <div class="relative">
                            <select name="pembayaran_id" required class="shadow-theme-xs h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-11 text-sm text-gray-800
                                       focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden
                                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="">Pilih pembayaran</option>
                                @foreach($pembayarans as $p)
                                    <option value="{{ $p->id }}">
                                        {{ optional($p->peserta->user)->name ?? '-' }}
                                        — {{ number_format($p->nominal, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>

                            <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-gray-500">
                                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M4.792 7.396L10 12.604l5.208-5.208" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Nomor Kwitansi --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nomor Kwitansi
                        </label>

                        <input name="nomor_kwitansi" type="text" required class="shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800
                                   focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden
                                   dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                    </div>

                    
                    {{-- File PDF --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            File PDF
                        </label>

                        <input name="file_pdf" type="file" accept="application/pdf" required class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300
                   h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent
                   text-sm text-gray-500 transition-colors
                   file:mr-5 file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r
                   file:border-gray-200 file:bg-gray-50 file:px-3.5 file:py-3
                   file:text-sm file:text-gray-700 hover:file:bg-gray-100
                   focus:outline-hidden
                   dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                   dark:file:border-gray-800 dark:file:bg-white/[0.03]
                   dark:file:text-gray-400" />
                    </div>


                    {{-- Action --}}
                    <div class="flex items-center gap-3 pt-4">
                        <button type="submit"
                            class="inline-flex items-center rounded-lg bg-brand-500 px-6 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
                            Simpan
                        </button>

                        <a href="{{ route('admin.kwitansi.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-6 py-3
                                   text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50
                                   dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                            Batal
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
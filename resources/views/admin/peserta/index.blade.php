@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

    <div class="rounded-2xl border border-gray-200 bg-white pt-4
                dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- Header --}}
        <div class="flex flex-col gap-2 px-5 mb-4 sm:flex-row sm:items-center
                    sm:justify-between sm:px-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Data Peserta
            </h3>
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
            <div id="success-alert"
                 class="fixed top-[15%] left-1/2 bg-green-50 border
                        border-green-200 text-green-800 px-6 py-3
                        rounded-full shadow-md flex items-center gap-2
                        transition-all duration-500 opacity-100 z-50"
                 style="transform: translateX(-50%);">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586
                             4.707 9.293a1 1 0 00-1.414 1.414l4 4
                             a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"
                          clip-rule="evenodd"/>
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

        {{-- Error --}}
        @if($errors->any())
            <div class="mx-5 mb-4 rounded-lg bg-red-50 px-4 py-3
                        text-sm text-red-600 dark:bg-red-500/15
                        dark:text-red-400 sm:mx-6">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Search & Action --}}
        <div class="flex flex-col gap-3 px-5 mb-4
                    sm:flex-row sm:items-center
                    sm:justify-between sm:px-6">

            {{-- Search --}}
            <form method="GET" class="w-full sm:max-w-md">
                <input name="q" value="{{ request('q') }}"
                       placeholder="Cari nama / email..."
                       class="h-[42px] w-full rounded-lg border
                              border-gray-300 bg-transparent
                              px-4 text-sm text-gray-800
                              placeholder:text-gray-400
                              focus:border-blue-300 focus:outline-none
                              focus:ring-2 focus:ring-blue-500/10
                              dark:border-gray-700
                              dark:bg-transparent
                              dark:text-white/90
                              dark:placeholder:text-gray-500">
            </form>

            {{-- Button Create --}}
            <div class="flex gap-2">
                @if(isset($context) && $context === 'kursus')
                    <a href="{{ route('admin.peserta.create', 'kursus') }}"
                       class="inline-flex h-[42px] items-center
                              justify-center rounded-lg
                              bg-brand-500 px-5 text-sm
                              font-medium text-white
                              shadow-theme-xs hover:bg-brand-600">
                        Tambah Kursus
                    </a>
                @elseif(isset($context) && $context === 'pkl')
                    <a href="{{ route('admin.peserta.create', 'pkl') }}"
                       class="inline-flex h-[42px] items-center
                              justify-center rounded-lg
                              bg-indigo-500 px-5 text-sm
                              font-medium text-white
                              shadow-theme-xs hover:bg-indigo-600">
                        Tambah PKL
                    </a>
                @else
                    <a href="{{ route('admin.peserta.create', 'kursus') }}"
                       class="inline-flex h-[42px] items-center
                              justify-center rounded-lg
                              bg-brand-500 px-5 text-sm
                              font-medium text-white
                              shadow-theme-xs hover:bg-brand-600">
                        Tambah Kursus
                    </a>

                    <a href="{{ route('admin.peserta.create', 'pkl') }}"
                       class="inline-flex h-[42px] items-center
                              justify-center rounded-lg
                              bg-indigo-500 px-5 text-sm
                              font-medium text-white
                              shadow-theme-xs hover:bg-indigo-600">
                        Tambah PKL
                    </a>
                @endif
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-hidden">
            <div class="max-w-full overflow-x-auto px-5 sm:px-6">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-y border-gray-200 dark:border-gray-700">
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500 dark:text-gray-400">Nama</th>
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500 dark:text-gray-400">Email</th>
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500 dark:text-gray-400">Jenis</th>
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500 dark:text-gray-400">Program</th>
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500 dark:text-gray-400">Jadwal</th>
                            <th class="px-4 py-3 text-left text-sm font-normal text-gray-500 dark:text-gray-400">Status</th>
                            <th class="px-4 py-3 text-right text-sm font-normal text-gray-500 dark:text-gray-400">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($pesertas as $peserta)
                            <tr>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $peserta->user->name }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $peserta->user->email }}
                                </td>
                                <td class="px-4 py-4">
                                    <span class="inline-flex rounded-full
                                                 bg-gray-100 px-2
                                                 text-xs font-semibold
                                                 text-gray-600
                                                 dark:bg-gray-500/15
                                                 dark:text-gray-300">
                                        {{ strtoupper($peserta->jenis) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $peserta->program }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $peserta->jadwal_display }}
                                </td>
                                <td class="px-4 py-4">
                                    @if ($peserta->status === 'pending')
                                        <span class="inline-flex rounded-full
                                                     bg-yellow-50 px-2
                                                     text-xs font-semibold
                                                     text-yellow-600
                                                     dark:bg-yellow-500/15
                                                     dark:text-orange-400">
                                            Pending
                                        </span>
                                    @elseif ($peserta->status === 'diterima')
                                        <span class="inline-flex rounded-full
                                                     bg-green-50 px-2
                                                     text-xs font-semibold
                                                     text-green-600
                                                     dark:bg-green-500/15
                                                     dark:text-green-500">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full
                                                     bg-gray-100 px-2
                                                     text-xs font-semibold
                                                     text-gray-600
                                                     dark:bg-gray-500/15
                                                     dark:text-gray-300">
                                            {{ ucfirst($peserta->status) }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-4 text-right">
                                    <div class="inline-flex items-center gap-2">

                                        @if ($peserta->status === 'pending')
                                            <a href="{{ route('admin.peserta.confirm', $peserta) }}"
                                               class="inline-flex items-center
                                                      rounded-lg bg-green-500
                                                      px-3 py-1.5 text-xs
                                                      font-medium text-white
                                                      shadow-theme-xs
                                                      hover:bg-green-600">
                                                Konfirmasi
                                            </a>
                                        @endif

                                        <a href="{{ route('admin.peserta.edit', $peserta) }}"
                                           class="inline-flex items-center
                                                  rounded-lg bg-yellow-500
                                                  px-3 py-1.5 text-xs
                                                  font-medium text-white
                                                  shadow-theme-xs
                                                  hover:bg-yellow-600">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.peserta.destroy', $peserta) }}"
                                              method="POST"
                                              onsubmit="return confirm('Hapus peserta ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center
                                                           rounded-lg bg-red-500
                                                           px-3 py-1.5 text-xs
                                                           font-medium text-white
                                                           shadow-theme-xs
                                                           hover:bg-red-600">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="px-4 py-6 text-center
                                           text-sm text-gray-500 dark:text-gray-400">
                                    Data peserta kosong
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="border-t border-gray-200 px-6 py-4
                    dark:border-white/[0.05]">
            {{ $pesertas->links() }}
        </div>

    </div>
</div>
@endsection

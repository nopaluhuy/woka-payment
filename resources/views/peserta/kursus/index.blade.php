@extends('layouts.app')

@section('title', 'Kursus Saya')

@section('content')
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Kursus Saya
            </h3>
        </div>

        {{-- Empty State --}}
        @if($pesertas->isEmpty())
            <div class="rounded-lg bg-yellow-50 px-4 py-3 text-sm text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400">
                Anda belum mengikuti kursus.
            </div>
        @else

            @error('alasan_keluar')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror

            {{-- List --}}
            <div class="space-y-4">
                @foreach($pesertas as $p)
                    <div
                        class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
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
                                        <td class="px-4 py-4 text-sm font-normal text-gray-900 dark:text-white">Jadwal</td>
                                        <td class="px-4 py-4 text-sm text-gray-500">{{ $p->jadwal_display }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-4 text-sm font-normal text-gray-900 dark:text-white">Biaya / bulan</td>
                                        <td class="px-4 py-4 text-sm text-gray-500">Rp
                                            {{ number_format($p->biaya_per_bulan, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-4 text-sm font-normal text-gray-900 dark:text-white">Durasi</td>
                                        <td class="px-4 py-4 text-sm text-gray-500">{{ $p->durasi_bulan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-4 text-sm font-normal text-gray-900 dark:text-white">Tanggal Masuk</td>
                                        <td class="px-4 py-4 text-sm text-gray-500">{{ $p->tanggal_masuk }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            {{-- Action --}}
                            @if($p->isActive())
                                <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700">
                                    <button type="button" onclick="openKeluarModal({{ $p->id }})"
                                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                                        Keluar Kursus
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    {{-- Modal Keluar Kursus --}}
                    <div id="keluarModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
                        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-lg dark:bg-gray-900">
                            <h4 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white">
                                Konfirmasi Keluar Kursus
                            </h4>

                            <form method="POST" action="{{ route('peserta.kursus.keluar') }}">
                                @csrf

                                <input type="hidden" name="peserta_id" id="peserta_id">

                                <div class="mb-4">
                                    <label class="mb-1 block text-sm text-gray-600 dark:text-gray-300">
                                        Alasan keluar kursus
                                    </label>
                                    <textarea name="alasan_keluar" required rows="3"
                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                        placeholder="Tuliskan alasan Anda..."></textarea>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="closeKeluarModal()"
                                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm dark:border-gray-700">
                                        Batal
                                    </button>

                                    <button type="submit"
                                        class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                                        Ya, Keluar
                                    </button>
                                </div>
                            </form>
                            <script>
                                function openKeluarModal(id) {
                                    document.getElementById('peserta_id').value = id;
                                    document.getElementById('keluarModal').classList.remove('hidden');
                                    document.getElementById('keluarModal').classList.add('flex');
                                }

                                function closeKeluarModal() {
                                    document.getElementById('keluarModal').classList.add('hidden');
                                    document.getElementById('keluarModal').classList.remove('flex');
                                }
                            </script>

                        </div>
                    </div>

                @endforeach
            </div>

        @endif
    </div>
@endsection
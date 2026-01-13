@extends('layouts.app')

@section('content')
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Tambah Peserta {{ ucfirst($jenis) }}
            </h3>
        </div>

        {{-- Body --}}
        <div class="p-6">
            <form action="{{ route('admin.peserta.store') }}" method="POST">
                @csrf
                <input type="hidden" name="jenis" value="{{ $jenis }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Nama --}}
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Peserta
                        </label>
                        <input type="text" name="name" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email
                        </label>
                        <input type="email" name="email" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Password
                        </label>
                        <input type="password" name="password" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                    </div>

                    {{-- Program --}}
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Program
                        </label>
                        <input type="text" name="program" required
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                    </div>

                    {{-- Jadwal --}}
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Jadwal
                        </label>
                        <input type="text" name="jadwal[]" value="{{ $jenis === 'pkl' ? 'Senin-Jumat 08.30-15.30' : '' }}"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                    </div>

                    {{-- No WA --}}
                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                            No WhatsApp
                        </label>
                        <input type="text" name="no_wa"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                    </div>

                    {{-- KHUSUS KURSUS --}}
                    @if ($jenis === 'kursus')
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Biaya Per Bulan
                            </label>
                            <div class="flex">
                                <span
                                    class="inline-flex items-center rounded-l-lg border border-r-0 border-gray-300 px-3 text-sm text-gray-600 dark:border-gray-700 dark:text-gray-300">
                                    Rp
                                </span>
                                <input type="text" name="biaya_per_bulan"
                                    class="rupiah-input w-full rounded-r-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm"
                                    inputmode="numeric" required>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tanggal Masuk
                            </label>
                            <input type="date" name="tanggal_masuk" required
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        </div>

                        {{-- NOTE KURSUS (DIKEMBALIKAN) --}}
                        <div class="md:col-span-2 text-sm text-gray-500 dark:text-gray-400">
                            Catatan: Kursus adalah langganan bulanan tanpa durasi, biarkan "Tanggal Keluar" kosong sampai
                            peserta keluar.
                        </div>
                    @endif

                    {{-- KHUSUS PKL --}}
                    @if ($jenis === 'pkl')
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Asal Sekolah
                            </label>
                            <input type="text" name="asal_sekolah" required
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        </div>

                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Durasi (bulan)
                            </label>
                            <input type="number" name="durasi_bulan" required
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        </div>

                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Biaya Per Bulan
                            </label>
                            <div class="flex">
                                <span
                                    class="inline-flex items-center rounded-l-lg border border-r-0 border-gray-300 px-3 text-sm text-gray-600 dark:border-gray-700 dark:text-gray-300">
                                    Rp
                                </span>
                                <input type="text" name="biaya_per_bulan"
                                    class="rupiah-input w-full rounded-r-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm"
                                    inputmode="numeric" required>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tanggal Masuk
                            </label>
                            <input type="date" name="tanggal_masuk" required
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm">
                        </div>
                    @endif
                </div>

                {{-- Action --}}
                <div class="mt-6 flex gap-3">
                    <button type="submit"
                        class="rounded-lg bg-blue-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-600">
                        Simpan
                    </button>
                    <a href="{{ route('admin.peserta.index') }}"
                        class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">
                        Kembali
                    </a>
                </div>
            </form>

            {{-- FORMAT RUPIAH --}}
            <script>
                document.querySelectorAll('.rupiah-input').forEach(function (input) {
                    input.addEventListener('input', function () {
                        let angka = this.value.replace(/\D/g, '');
                        let sisa = angka.length % 3;
                        let rupiah = angka.substr(0, sisa);
                        let ribuan = angka.substr(sisa).match(/\d{3}/g);

                        if (ribuan) {
                            rupiah += (sisa ? '.' : '') + ribuan.join('.');
                        }

                        this.value = rupiah;
                    });
                });
            </script>

        </div>
    </div>
@endsection
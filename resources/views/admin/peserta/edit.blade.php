@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Edit Peserta" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <h3 class="mb-6 text-lg font-semibold text-gray-800 dark:text-white/90">
            Edit Peserta
        </h3>

        <form action="{{ route('admin.peserta.update', $peserta) }}" method="POST" class="space-y-6" id="formPeserta">
            @csrf
            @method('PUT')

            {{-- Pengguna --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Pengguna</label>
                <select name="user_id" required class="mt-2 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm
                                   dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}" @selected($u->id == $peserta->user_id)>
                            {{ $u->name }} — {{ $u->email }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Jenis & Program --}}
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Jenis</label>
                    <select name="jenis" id="jenisEdit" required class="mt-2 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm
                                       dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <option value="kursus" @selected($peserta->jenis === 'kursus')>Kursus</option>
                        <option value="pkl" @selected($peserta->jenis === 'pkl')>PKL</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Program</label>
                    <input name="program" value="{{ $peserta->program }}" class="mt-2 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm
                                       dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>
            </div>

            {{-- Asal Sekolah --}}
            <div id="wrapAsalSekolah">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Asal Sekolah</label>
                <input name="asal_sekolah" value="{{ $peserta->asal_sekolah }}" class="mt-2 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm
                                   dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>

            {{-- Jadwal --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Jadwal</label>
                <input name="jadwal[]"
                    value="{{ is_array($peserta->jadwal) ? implode(', ', $peserta->jadwal) : $peserta->jadwal }}" class="mt-2 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm
                                   dark:border-gray-700 dark:bg-gray-900 dark:text-white">
            </div>

            {{-- BIAYA / DURASI / TANGGAL --}}
            <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
                {{-- BIAYA --}}
                <div id="wrapBiaya">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Biaya Per Bulan
                    </label>

                    <div class="mt-2 flex">
                        <span class="inline-flex items-center rounded-l-lg border border-r-0 border-gray-300 px-3 text-sm text-gray-600
                                             dark:border-gray-700 dark:text-gray-300">
                            Rp
                        </span>

                        <input type="text" name="biaya_per_bulan" id="biaya_per_bulan"
                            value="{{ number_format($peserta->biaya_per_bulan, 0, ',', '.') }}" inputmode="numeric" class="w-full rounded-r-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900
                                           focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10
                                           dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    </div>
                </div>

                {{-- DURASI --}}
                <div id="wrapDurasi">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Durasi Bulan</label>
                    <input name="durasi_bulan" type="number" value="{{ $peserta->durasi_bulan }}" class="mt-2 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm
                                       dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                </div>

                {{-- TANGGAL MASUK --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Masuk</label>
                    <input name="tanggal_masuk" type="date"
                        value="{{ $peserta->tanggal_masuk ? \Carbon\Carbon::parse($peserta->tanggal_masuk)->format('Y-m-d') : '' }}"
                        class="mt-2 block w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm
                      dark:border-gray-700 dark:bg-gray-900 dark:text-white">

                </div>

            </div>

            {{-- ACTION --}}
            <div class="flex items-center gap-3 pt-4">
                <button type="submit" class="rounded bg-brand-500 px-6 py-2 text-white hover:bg-brand-600">
                    Simpan
                </button>

                <a href="{{ route('admin.peserta.index') }}" class="rounded border border-gray-300 px-6 py-2 text-gray-700 hover:bg-gray-50
                                   dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/[0.05]">
                    Kembali
                </a>
            </div>
        </form>
    </div>

    {{-- ================= SCRIPT LANGSUNG (PASTI JALAN) ================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jenis = document.getElementById('jenisEdit');
            const asal = document.getElementById('wrapAsalSekolah');
            const durasi = document.getElementById('wrapDurasi');
            const biaya = document.getElementById('biaya_per_bulan');
            const form = document.getElementById('formPeserta');

            function toggleField() {
                if (jenis.value === 'kursus') {
                    asal.style.display = 'none';
                    durasi.style.display = 'none';
                } else {
                    asal.style.display = '';
                    durasi.style.display = '';
                }
            }

            toggleField();
            jenis.addEventListener('change', toggleField);

            function formatRupiah(angka) {
                return angka.replace(/\D/g, '')
                    .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            // REALTIME FORMAT
            biaya.addEventListener('keyup', function () {
                this.value = formatRupiah(this.value);
            });

            // CLEAN SAAT SUBMIT
            form.addEventListener('submit', function () {
                biaya.value = biaya.value.replace(/\D/g, '');
            });
        });
    </script>
@endsection
@extends('layouts.fullscreen-layout')

@section('content')
    <div class="relative z-1 min-h-screen bg-gray-100 p-4 dark:bg-gray-900">
        <div class="flex min-h-screen items-center justify-center">

            <!-- CARD -->
            <div class="w-full max-w-lg rounded-xl bg-gray-50 border border-gray-200
                        p-6 shadow-theme-xs dark:bg-gray-900 dark:border-gray-700 sm:p-8">


                <!-- BACK -->
                <a href="/" class="mb-6 inline-flex items-center text-sm text-gray-500 transition-colors
                                  hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                    <svg class="mr-1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                        fill="none">
                        <path d="M12.7083 5L7.5 10.2083L12.7083 15.4167" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Kembali
                </a>

                <!-- TITLE -->
                <div class="mb-6">
                    <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">
                        Daftar Kursus
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Silakan isi data di bawah untuk mendaftar
                    </p>
                </div>

                <!-- ERROR -->
                @if ($errors->any())
                    <div class="mb-5 rounded-lg border border-error-200 bg-error-50
                                                    p-4 text-sm text-error-700">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORM -->
                <form action="{{ route('register.kursus.store') }}" method="POST">
                    @csrf

                    <div class="space-y-4">

                        <!-- Nama -->
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nama
                            </label>
                            <input name="name" value="{{ old('name') }}" required class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm
                                               text-gray-800 placeholder:text-gray-400 focus:border-brand-300
                                               focus:ring-3 focus:ring-brand-500/10 focus:outline-none
                                               dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Email
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm
                                               text-gray-800 placeholder:text-gray-400 focus:border-brand-300
                                               focus:ring-3 focus:ring-brand-500/10 focus:outline-none
                                               dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Password
                            </label>
                            <input type="password" name="password" required class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm
                                               text-gray-800 focus:border-brand-300 focus:ring-3
                                               focus:ring-brand-500/10 focus:outline-none
                                               dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        <!-- Konfirmasi -->
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Konfirmasi Password
                            </label>
                            <input type="password" name="password_confirmation" required class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm
                                               text-gray-800 focus:border-brand-300 focus:ring-3
                                               focus:ring-brand-500/10 focus:outline-none
                                               dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        <!-- Program -->
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Program
                            </label>
                            <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">
                                Pilih salah satu program: Web Development, Data Science, UI/UX, Mobile App.
                            </p>
                            <input name="program" value="{{ old('program') }}" required class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm
                                    text-gray-800 focus:border-brand-300 focus:ring-3
                                    focus:ring-brand-500/10 focus:outline-none
                                    dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>


                        <!-- WA -->
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nomor WhatsApp
                            </label>
                            <input name="no_wa" value="{{ old('no_wa') }}" class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm
                                               text-gray-800 focus:border-brand-300 focus:ring-3
                                               focus:ring-brand-500/10 focus:outline-none
                                               dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        <!-- Jadwal -->
                        <div>
                            <div id="jadwal-wrapper" class="space-y-2">
                                <select name="jadwal[]" required
                                    class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 text-sm">
                                    <option value="">-- Pilih Jadwal --</option>
                                    @foreach ($jadwals as $jadwal)
                                        <option value="{{ $jadwal['label'] }}">
                                            {{ $jadwal['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="button" onclick="addJadwal()" class="mt-2 text-sm text-brand-500 hover:underline">
                                + Tambah Jadwal
                            </button>



                        </div>

                        <!-- Button -->
                        <div class="pt-2">
                            <button class="flex w-full items-center justify-center rounded-lg
                                               bg-brand-500 px-4 py-3 text-sm font-medium
                                               text-white shadow-theme-xs transition
                                               hover:bg-brand-600">
                                Daftar
                            </button>
                        </div>

                    </div>
                </form>
<script>
function addJadwal() {
    const wrapper = document.getElementById('jadwal-wrapper');
    const select = wrapper.firstElementChild.cloneNode(true);
    select.value = '';
    wrapper.appendChild(select);
}
</script>


            </div>
            <!-- END CARD -->

        </div>
    </div>
@endsection
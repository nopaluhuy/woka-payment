@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Admin Profile" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">Edit Admin Profile</h3>

        @if(session('success'))
            <div id="success-alert" class="fixed top-[15%] left-1/2 
                   bg-green-50 border border-green-200 text-green-800 
                   px-6 py-3 rounded-full shadow-md flex items-center gap-2
                   transition-all duration-500 opacity-100 z-50" style="transform: translateX(-50%);">
                <!-- Icon centang -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M16.707 5.293a1 1 0 00-1.414 0L8 12.586 4.707 9.293a1 1 0 00-1.414 1.414l4 4a1 1 0 001.414 0l8-8a1 1 0 000-1.414z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>

            <script>
                setTimeout(function () {
                    const alert = document.getElementById('success-alert');
                    alert.style.opacity = '0';
                    // Tetap center horizontal, naik lurus ke atas
                    alert.style.transform = 'translateX(-50%) translateY(-50px)';
                    setTimeout(() => alert.remove(), 500);
                }, 2000);
            </script>
        @endif


        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Name</label>
                    <input name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm px-4 py-2
                                           dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                    <input name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm px-4 py-2
                                           dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                </div>


                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Password (leave blank to keep current)
                    </label>
                    <input type="password" name="password" class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm px-4 py-2
                                           dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm px-4 py-2
                                           dark:border-gray-700 dark:bg-gray-900 dark:text-white" />
                </div>
            </div>

            <div class="mt-4">
                <button class="rounded bg-brand-500 px-6 py-2 text-white hover:bg-brand-600">Save</button>
            </div>
        </form>
    </div>
@endsection
@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Peserta Profile" />

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] lg:p-6">
        <h3 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">Edit Profile</h3>

        @if(session('success'))
            <div class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('peserta.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Name</label>
                    <input 
                        name="name" 
                        value="{{ old('name', $user->name) }}"
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm
                               px-4 py-2 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                    <input 
                        name="email" 
                        value="{{ old('email', $user->email) }}"
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm
                               px-4 py-2 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Password (leave blank to keep current)
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm
                               px-4 py-2 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Confirm Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm
                               px-4 py-2 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">No WA</label>
                    <input 
                        name="no_wa" 
                        value="{{ old('no_wa', $user->no_wa) }}"
                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white text-gray-900 shadow-sm
                               px-4 py-2 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                    />
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="rounded bg-brand-500 px-6 py-2 text-white hover:bg-brand-600">
                    Save
                </button>
            </div>
        </form>
    </div>
@endsection

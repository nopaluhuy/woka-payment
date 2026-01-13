<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} | WokaPayment</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/logoPY.png') }}">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js Theme & Sidebar Store -->
    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.store('theme', {
                theme: 'light',

                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches
                        ? 'dark'
                        : 'light';

                    this.theme = savedTheme || systemTheme;
                    this.apply();
                },

                toggle() {
                    this.theme = this.theme === 'dark' ? 'light' : 'dark';
                    localStorage.setItem('theme', this.theme);
                    this.apply();
                },

                apply() {
                    const html = document.documentElement;

                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                    } else {
                        html.classList.remove('dark');
                    }

                    // BODY AMAN (pasti ada karena Alpine jalan setelah DOM siap)
                    document.body.classList.toggle('bg-gray-900', this.theme === 'dark');
                }
            });

            Alpine.store('sidebar', {
                isExpanded: window.innerWidth >= 1280,
                isMobileOpen: false,
                isHovered: false,

                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    this.isMobileOpen = false;
                },

                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                },

                setMobileOpen(val) {
                    this.isMobileOpen = val;
                },

                setHovered(val) {
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                }
            });
        });
    </script>

    <!-- PREVENT FLASH DARK MODE (SAFE) -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches
                ? 'dark'
                : 'light';

            const theme = savedTheme || systemTheme;

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

</head>

<body
    x-data="{ loaded: true }"
    x-init="
        $store.sidebar.isExpanded = window.innerWidth >= 1280;

        const checkMobile = () => {
            if (window.innerWidth < 1280) {
                $store.sidebar.setMobileOpen(false);
                $store.sidebar.isExpanded = false;
            } else {
                $store.sidebar.isMobileOpen = false;
                $store.sidebar.isExpanded = true;
            }
        };

        window.addEventListener('resize', checkMobile);
    "
    class="bg-gray-100 dark:bg-gray-900"
>

    {{-- PRELOADER --}}
    <x-common.preloader />

    <div class="min-h-screen xl:flex">

        {{-- BACKDROP & SIDEBAR --}}
        @include('layouts.backdrop')
        @include('layouts.sidebar')

        <div
            class="flex-1 transition-all duration-300 ease-in-out"
            :class="{
                'xl:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'xl:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'ml-0': $store.sidebar.isMobileOpen
            }"
        >
            {{-- HEADER --}}
            @include('layouts.app-header')

            <main class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
                @yield('content')
            </main>
        </div>

    </div>

    @stack('scripts')
</body>

</html>

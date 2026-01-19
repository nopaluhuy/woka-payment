<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} | WokaPayment</title>

    <link rel="icon" type="image/png" href="{{ asset('assets/logoPY.png') }}">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine Theme & Sidebar Store -->
    <script>
        document.addEventListener('alpine:init', () => {

            /* ======================
               THEME STORE (SAFE)
            ====================== */
            Alpine.store('theme', {
                theme: 'light',

                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches
                        ? 'dark'
                        : 'light';

                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },

                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },

                updateTheme() {
                    const html = document.documentElement;
                    const body = document.body;

                    if (!html || !body) return;

                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                        body.classList.add('dark', 'bg-gray-900');
                    } else {
                        html.classList.remove('dark');
                        body.classList.remove('dark', 'bg-gray-900');
                    }
                }
            });

            /* ======================
               SIDEBAR STORE (SAFE)
               (Login page won't use it)
            ====================== */
            Alpine.store('sidebar', {
                isExpanded: false,
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

    <!-- Apply dark mode early (SAFE) -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches
                ? 'dark'
                : 'light';

            const theme = savedTheme || systemTheme;

            const html = document.documentElement;

            if (theme === 'dark') {
                html.classList.add('dark');
                if (document.body) {
                    document.body.classList.add('dark', 'bg-gray-900');
                }
            } else {
                html.classList.remove('dark');
                if (document.body) {
                    document.body.classList.remove('dark', 'bg-gray-900');
                }
            }
        })();
    </script>
</head>

<body
    x-data="{ loaded: true }"
    x-init="
        if ($store.sidebar) {
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
        }
    "
>

    {{-- Preloader (aman walau login) --}}
    <x-common.preloader />

    {{-- Content --}}
    @yield('content')

    @stack('scripts')
</body>
</html>

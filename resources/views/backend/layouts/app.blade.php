<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', ($globalSettings['app_name'] ?? 'SIM Kendaraan') . ' - Dashboard')</title>

    @if(!empty($globalSettings['app_logo']))
        <link rel="icon" href="{{ asset('storage/' . ltrim($globalSettings['app_logo'], '/')) }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100"
    x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Backdrop (Mobile Only) -->
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-20 bg-slate-900/80 md:hidden backdrop-blur-sm"
            @click="sidebarOpen = false" aria-hidden="true" style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-72 transition-transform duration-300 ease-in-out bg-slate-900 border-r border-slate-800 md:static md:translate-x-0 flex flex-col">
            @include('backend.components.sidebar')
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header -->
            @include('backend.components.navbar')

            <!-- Dashboard Content -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-8">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </main>
    </div>
    @livewireScripts

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Membaca Flash Message Standar (Non-Livewire)
        @if(session()->has('message'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: 'success',
                title: "{!! session('message') !!}"
            });
        @endif

        // Mendengarkan Event Dispatch dari Komponen Livewire
        document.addEventListener('livewire:init', () => {
            Livewire.on('swal', (event) => {
                let data = event[0] || event;
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: data.icon || 'success',
                    title: data.title || 'Informasi',
                    text: data.text || ''
                });
            });
        });
    </script>
</body>

</html>
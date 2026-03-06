<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ request()->cookie('theme', 'light') }}">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>@yield('title', $globalSettings['app_name'] ?? 'SIM Kendaraan') - Mengakselerasi Performa</title>

  @if(!empty($globalSettings['app_logo']))
    <link rel="icon" href="{{ asset('storage/' . ltrim($globalSettings['app_logo'], '/')) }}">
  @endif

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />

  <style>
    .material-symbols-outlined {
      font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
  </style>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100">

  <!-- Navigation Header (Harus di luar overflow-hidden agar sticky jalan) -->
  @include('frontend.components.header')

  <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">


      <!-- Main Content -->
      <main class="flex flex-1 flex-col items-center">
        @yield('content')
      </main>

      <!-- Footer -->
      @include('frontend.components.footer')
    </div>
  </div>

  @livewireScripts
</body>

</html>
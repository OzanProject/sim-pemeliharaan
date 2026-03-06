<header x-data="{ mobileMenuOpen: false }"
  class="relative flex items-center justify-between border-b border-solid border-slate-200/80 dark:border-slate-800/80 bg-white/90 dark:bg-background-dark/90 backdrop-blur-md px-6 md:px-10 py-4 sticky top-0 z-50 shadow-sm">

  <!-- Logo -->
  <a href="{{ url('/') }}" class="flex items-center gap-3 z-50">
    @if(!empty($globalSettings['app_logo']))
      <img src="{{ asset('storage/' . ltrim($globalSettings['app_logo'], '/')) }}" alt="Logo"
        class="w-8 h-8 rounded-lg object-contain bg-white">
    @else
      <div class="text-primary size-8">
        <svg fill="currentColor" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
          <path
            d="M24 4C25.7818 14.2173 33.7827 22.2182 44 24C33.7827 25.7818 25.7818 33.7827 24 44C22.2182 33.7827 14.2173 25.7818 4 24C14.2173 22.2182 22.2182 14.2173 24 4Z">
          </path>
        </svg>
      </div>
    @endif
    <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">
      {{ $globalSettings['app_name'] ?? 'SIM Kendaraan' }}
    </h2>
  </a>

  <!-- Desktop Navigation -->
  <div class="hidden md:flex flex-1 justify-end items-center gap-8">
    <nav class="flex items-center gap-9">
      <a class="text-sm font-medium transition-colors {{ request()->is('/') ? 'text-primary' : 'text-slate-700 dark:text-slate-300 hover:text-primary' }}"
        href="{{ url('/') }}">Beranda</a>
      <a class="text-sm font-medium transition-colors {{ request()->is('layanan') ? 'text-primary' : 'text-slate-700 dark:text-slate-300 hover:text-primary' }}"
        href="{{ url('/layanan') }}">Layanan</a>
      <a class="text-sm font-medium transition-colors {{ request()->is('tentang-kami') ? 'text-primary' : 'text-slate-700 dark:text-slate-300 hover:text-primary' }}"
        href="{{ url('/tentang-kami') }}">Tentang Kami</a>
    </nav>
    <div class="flex items-center gap-4">
      @if (Route::has('login'))
        @auth
          <a href="{{ url('/dashboard') }}"
            class="flex min-w-[100px] cursor-pointer items-center justify-center rounded-lg h-10 px-5 bg-primary text-white text-sm font-bold transition-all hover:brightness-110 shadow-sm whitespace-nowrap">
            <span>Dashboard</span>
          </a>
        @else
          <a href="{{ route('login') }}"
            class="flex min-w-[100px] cursor-pointer items-center justify-center rounded-lg h-10 px-5 border border-primary text-primary hover:bg-primary hover:text-white text-sm font-bold transition-all shadow-sm whitespace-nowrap">
            <span>Login</span>
          </a>
        @endauth
      @endif
    </div>
  </div>

  <!-- Mobile Hamburger Button -->
  <div class="md:hidden flex items-center z-50">
    <button @click="mobileMenuOpen = !mobileMenuOpen"
      class="text-slate-700 dark:text-slate-300 hover:text-primary focus:outline-none p-2 rounded-lg bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
      <span class="material-symbols-outlined" x-show="!mobileMenuOpen">menu</span>
      <span class="material-symbols-outlined" x-show="mobileMenuOpen" style="display: none;">close</span>
    </button>
  </div>

  <!-- Mobile Dropdown Menu -->
  <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="absolute top-full left-0 right-0 bg-white dark:bg-background-dark border-b border-slate-200 dark:border-slate-800 shadow-xl md:hidden flex flex-col p-6 gap-6"
    style="display: none;">

    <nav class="flex flex-col gap-4">
      <a class="text-base font-semibold py-2 border-b border-slate-100 dark:border-slate-800 transition-colors {{ request()->is('/') ? 'text-primary' : 'text-slate-700 dark:text-slate-300 hover:text-primary' }}"
        href="{{ url('/') }}">Beranda</a>
      <a class="text-base font-semibold py-2 border-b border-slate-100 dark:border-slate-800 transition-colors {{ request()->is('layanan') ? 'text-primary' : 'text-slate-700 dark:text-slate-300 hover:text-primary' }}"
        href="{{ url('/layanan') }}">Layanan</a>
      <a class="text-base font-semibold py-2 border-b border-slate-100 dark:border-slate-800 transition-colors {{ request()->is('tentang-kami') ? 'text-primary' : 'text-slate-700 dark:text-slate-300 hover:text-primary' }}"
        href="{{ url('/tentang-kami') }}">Tentang Kami</a>
    </nav>

    <div class="flex flex-col gap-4 mt-2">
      @if (Route::has('login'))
        @auth
          <a href="{{ url('/dashboard') }}"
            class="flex w-full cursor-pointer items-center justify-center rounded-lg h-12 px-5 bg-primary text-white text-base font-bold transition-all shadow-sm">
            <span>Dashboard</span>
          </a>
        @else
          <a href="{{ route('login') }}"
            class="flex w-full cursor-pointer items-center justify-center rounded-lg h-12 px-5 border-2 border-primary text-primary hover:bg-primary hover:text-white text-base font-bold transition-all shadow-sm">
            <span>Masuk Sistem</span>
          </a>
        @endauth
      @endif
    </div>
  </div>
</header>
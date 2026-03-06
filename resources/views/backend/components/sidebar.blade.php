<div class="p-6 flex items-center justify-between gap-3 shrink-0">
    <div class="p-6 border-b border-white/5 flex items-center gap-3">
        @if(!empty($globalSettings['app_logo']))
            <img src="{{ asset('storage/' . ltrim($globalSettings['app_logo'], '/')) }}" alt="Logo"
                class="w-8 h-8 rounded-lg object-contain bg-white">
        @else
            <div class="w-8 h-8 rounded-lg bg-primary/20 text-primary flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
            </div>
        @endif
        <div>
            <h1 class="text-sm font-bold leading-tight uppercase tracking-wider text-primary truncate w-40"
                title="{{ $globalSettings['app_name'] ?? 'SIM Kendaraan' }}">
                {{ $globalSettings['app_name'] ?? 'SIM Kendaraan' }}
            </h1>
            <p class="text-[10px] text-slate-400 font-medium tracking-wide">Sistem Pengelolaan</p>
        </div>
    </div>
    <button @click="sidebarOpen = false" class="md:hidden p-1 text-slate-400 hover:text-slate-600 rounded-lg">
        <span class="material-symbols-outlined">close</span>
    </button>
</div>

<style>
    /* Hide scrollbar for Chrome, Safari and Opera */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    .no-scrollbar {
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }
</style>

<nav class="flex-1 px-4 space-y-1 overflow-y-auto no-scrollbar mt-4 pb-4">
    <!-- Grup Utama -->
    <div class="px-3 pt-4 pb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Menu Utama</div>
    <a href="{{ route('dashboard') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary text-white font-medium shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors' }}">
        <span class="material-symbols-outlined">dashboard</span>
        <span class="text-sm">Dashboard</span>
    </a>

    <!-- Grup Transaksi -->
    <div class="px-3 pt-4 pb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Transaksi</div>
    <a href="{{ route('expenses.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('expenses.*') ? 'bg-primary text-white font-medium shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors' }}">
        <span class="material-symbols-outlined">shopping_cart_checkout</span>
        <span class="text-sm">Input & Data Belanja</span>
    </a>

    <!-- Grup Master -->
    <div class="px-3 pt-4 pb-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Master</div>
    <a href="{{ route('budgets.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('budgets.*') ? 'bg-primary text-white font-medium shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors' }}">
        <span class="material-symbols-outlined">payments</span>
        <span class="text-sm">Input Anggaran</span>
    </a>
    <a href="{{ route('units.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('units.*') ? 'bg-primary text-white font-medium shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors' }}">
        <span class="material-symbols-outlined">directions_car</span>
        <span class="text-sm">Data Kendaraan</span>
    </a>

    <!-- Grup Laporan -->
    <div
        class="px-3 pt-4 pb-2 border-t border-slate-100 dark:border-slate-800 mt-4 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
        Lainnya</div>
    <a href="{{ route('reports.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('reports.*') ? 'bg-primary text-white font-medium shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors' }}">
        <span class="material-symbols-outlined">description</span>
        <span class="text-sm">Laporan</span>
    </a>

    <!-- Menu Khusus Admin -->
    @if(auth()->check() && auth()->user()->id === 1)
        <a href="{{ route('settings.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('settings.index') ? 'bg-primary text-white font-medium shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors' }}">
            <span class="material-symbols-outlined">settings</span>
            <span class="text-sm">Pengaturan Sistem</span>
        </a>
        <a href="{{ route('settings.frontend') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('settings.frontend') ? 'bg-primary text-white font-medium shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors' }}">
            <span class="material-symbols-outlined">brush</span>
            <span class="text-sm">Pengaturan Web</span>
        </a>
        <a href="{{ route('users.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('users.*') ? 'bg-primary text-white font-medium shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors' }}">
            <span class="material-symbols-outlined">manage_accounts</span>
            <span class="text-sm">Manajemen User</span>
        </a>
    @endif
</nav>

<div class="p-4 border-t border-slate-100 dark:border-slate-800 shrink-0">
    <div class="flex items-center gap-3 p-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
        <div class="size-10 rounded-full bg-slate-700 flex items-center justify-center text-slate-300 font-bold">
            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
        </div>
        <div class="overflow-hidden flex-1">
            <p class="text-sm font-semibold text-slate-200 truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
            <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email ?? 'admin@sim-kendaraan.id' }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="mt-2 text-center">
        @csrf
        <button type="submit"
            class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-red-400 hover:text-red-300 hover:bg-slate-800 transition-colors">
            <span class="material-symbols-outlined text-sm">logout</span>
            <span class="text-sm font-medium">Keluar</span>
        </button>
    </form>
</div>
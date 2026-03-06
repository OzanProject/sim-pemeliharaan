<div class="p-6 flex items-center gap-3 shrink-0">
  <div class="size-10 rounded-lg bg-primary flex items-center justify-center text-white">
    <span class="material-symbols-outlined">account_balance_wallet</span>
  </div>
  <div>
    <h1 class="text-sm font-bold leading-tight uppercase tracking-wider text-primary">BudgetPro</h1>
    <p class="text-xs text-slate-500">Sistem Pengelolaan</p>
  </div>
</div>

<nav class="flex-1 px-4 space-y-1 overflow-y-auto mt-4">
  <a href="{{ route('dashboard') }}"
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary text-white font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors' }}"
    wire:navigate>
    <span class="material-symbols-outlined">dashboard</span>
    <span class="text-sm">Dashboard</span>
  </a>

  <a href="#"
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->is('units*') ? 'bg-primary text-white font-medium shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors' }}">
    <span class="material-symbols-outlined">directions_car</span>
    <span class="text-sm">Data Kendaraan</span>
  </a>

  <a href="#"
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->is('budgets*') ? 'bg-primary text-white font-medium shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors' }}">
    <span class="material-symbols-outlined">payments</span>
    <span class="text-sm">Data Anggaran</span>
  </a>

  <a href="#"
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->is('expenses*') ? 'bg-primary text-white font-medium shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors' }}">
    <span class="material-symbols-outlined">shopping_bag</span>
    <span class="text-sm">Realisasi Belanja</span>
  </a>

  <a href="#"
    class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->is('reports*') ? 'bg-primary text-white font-medium shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors' }}">
    <span class="material-symbols-outlined">description</span>
    <span class="text-sm">Laporan</span>
  </a>

  <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-800">
    <a href="{{ route('profile') }}"
      class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
      wire:navigate>
      <span class="material-symbols-outlined">settings</span>
      <span class="text-sm">Pengaturan</span>
    </a>
  </div>
</nav>

<div class="p-4 border-t border-slate-100 dark:border-slate-800 shrink-0">
  <div class="flex items-center gap-3 p-2 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
    <div class="size-10 rounded-full bg-slate-700 flex items-center justify-center text-slate-300">
      <span class="material-symbols-outlined text-xl">person</span>
    </div>
    <div class="overflow-hidden"
      x-data="{{ json_encode(['name' => auth()->user()->name, 'email' => auth()->user()->email]) }}"
      x-on:profile-updated.window="name = $event.detail.name; email = $event.detail.email">
      <p class="text-sm font-semibold text-slate-200 truncate" x-text="name"></p>
      <p class="text-xs text-slate-500 truncate" x-text="email"></p>
    </div>
  </div>
</div>
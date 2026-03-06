<header
    class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 sm:px-8 shrink-0">

    <div class="flex items-center flex-1 gap-2 sm:gap-4">
        <!-- Hamburger (Mobile Only) -->
        <button @click="sidebarOpen = !sidebarOpen" type="button"
            class="inline-flex items-center justify-center p-2 text-slate-500 dark:text-slate-400 rounded-md md:hidden hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary transition-colors">
            <span class="sr-only">Open sidebar</span>
            <span class="material-symbols-outlined">menu</span>
        </button>

        <!-- Search Bar -->
        <div class="hidden sm:block flex-1 max-w-xl">
            <div class="relative group">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
                <input
                    class="w-full bg-slate-100 dark:bg-slate-800 border-none text-slate-900 dark:text-slate-200 rounded-lg py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all placeholder-slate-500"
                    placeholder="Cari data anggaran, unit, atau laporan..." type="text">
            </div>
        </div>
    </div>

    <div class="flex items-center gap-2 sm:gap-4 shrink-0">
        <!-- Notification Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
            <button @click="open = ! open"
                class="p-2 text-slate-500 hover:text-primary dark:hover:text-primary hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full relative transition-colors focus:ring-2 focus:ring-primary/20 outline-none">
                <span class="material-symbols-outlined text-[22px]">notifications</span>

                @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                    <span
                        class="absolute top-1 right-1.5 size-2.5 bg-red-500 rounded-full ring-2 ring-white dark:ring-slate-900 animate-pulse"></span>
                @endif
            </button>

            <!-- Dropdown Panel -->
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                class="absolute right-0 mt-2 w-80 sm:w-96 rounded-2xl bg-white dark:bg-slate-900 shadow-xl border border-slate-100 dark:border-slate-800 z-50 overflow-hidden"
                style="display: none;">

                <div
                    class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">Notifikasi</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pemberitahuan terbaru Anda</p>
                    </div>
                    @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                        <form method="POST" action="{{ route('notifications.readAll') }}">
                            @csrf
                            <button type="submit"
                                class="text-xs font-semibold text-primary hover:text-indigo-700 transition-colors">
                                Tandai dibaca
                            </button>
                        </form>
                    @endif
                </div>

                <div class="max-h-80 overflow-y-auto overscroll-contain">
                    @if(auth()->check() && auth()->user()->notifications->count() > 0)
                        <div class="divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach(auth()->user()->notifications->take(5) as $notification)
                                <a href="{{ route('expenses.index') }}"
                                    class="flex items-start gap-3 p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors {{ is_null($notification->read_at) ? 'bg-indigo-50/30 dark:bg-indigo-900/10' : '' }}">
                                    <div
                                        class="size-8 rounded-full flex shrink-0 items-center justify-center {{ is_null($notification->read_at) ? 'bg-primary/10 text-primary' : 'bg-slate-100 dark:bg-slate-800 text-slate-400' }}">
                                        <span class="material-symbols-outlined text-[18px]">payments</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-sm font-medium text-slate-900 dark:text-slate-100 leading-snug {{ is_null($notification->read_at) ? 'font-semibold' : '' }}">
                                            {{ $notification->data['message'] ?? 'Pemberitahuan Baru' }}
                                        </p>
                                        <div class="flex items-center gap-2 mt-1 hidden sm:flex">
                                            <span
                                                class="text-xs text-slate-500 truncate">{{ $notification->data['description'] ?? '' }}</span>
                                        </div>
                                        <p class="text-[10px] text-slate-400 font-medium mt-1.5 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[12px]">schedule</span>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    @if(is_null($notification->read_at))
                                        <div class="size-2 bg-primary rounded-full shrink-0 animate-pulse mt-2"></div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 text-center flex flex-col items-center justify-center">
                            <div
                                class="size-12 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-300 mb-3">
                                <span class="material-symbols-outlined text-2xl">notifications_paused</span>
                            </div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada notifikasi.</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Nanti notifikasi masuk akan tampil di
                                sini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="h-6 w-px bg-slate-200 dark:bg-slate-700 hidden sm:block mx-1"></div>

        <!-- User Dropdown (Custom Alpine) -->
        <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
            <button @click="open = ! open"
                class="flex items-center gap-2 sm:gap-3 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800 px-2 py-1.5 rounded-xl transition-colors text-slate-600 dark:text-slate-300 border border-transparent hover:border-slate-100 dark:hover:border-slate-700 focus:outline-none">
                @if (Auth::user() && Auth::user()->avatar)
                    <img src="{{ asset(Auth::user()->avatar) }}" alt="Avatar"
                        class="size-8 rounded-full object-cover shadow-sm ring-2 ring-white dark:ring-slate-900 border border-slate-200 dark:border-slate-700">
                @else
                    <div
                        class="size-8 rounded-full bg-gradient-to-tr from-primary to-indigo-400 flex items-center justify-center text-white font-bold text-xs shadow-sm ring-2 ring-white dark:ring-slate-900">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                @endif
                <div class="hidden sm:flex flex-col items-start leading-tight">
                    <span
                        class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ Auth::user()->name ?? 'Administrator' }}</span>
                    <span class="text-[10px] text-slate-400">Admin</span>
                </div>
                <span class="material-symbols-outlined text-slate-400 hidden sm:block transition-transform duration-200"
                    :class="{'rotate-180': open}">expand_more</span>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                class="absolute right-0 z-50 mt-2 w-56 rounded-xl bg-white dark:bg-slate-800 shadow-xl border border-slate-100 dark:border-slate-700 py-2 origin-top-right ring-1 ring-black ring-opacity-5 focus:outline-none"
                style="display: none;">

                <!-- User Info Header -->
                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700/50">
                    <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">
                        {{ Auth::user()->name ?? 'Administrator' }}
                    </p>
                    <p class="text-xs text-slate-500 truncate mt-0.5">{{ Auth::user()->email ?? 'admin@sim-kendaraan.id'
                        }}</p>
                </div>

                <!-- Links -->
                <div class="py-1">
                    <a href="{{ route('profile') }}"
                        class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <span class="material-symbols-outlined text-[18px] text-slate-400">person</span>
                        Profil Saya
                    </a>
                </div>

                <!-- Logout -->
                <div class="border-t border-slate-100 dark:border-slate-700/50 py-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors text-left">
                            <span class="material-symbols-outlined text-[18px]">logout</span>
                            Keluar Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
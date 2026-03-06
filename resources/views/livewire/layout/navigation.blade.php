<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<header class="h-16 bg-slate-900 border-b border-slate-800 flex items-center justify-between px-4 sm:px-8 shrink-0">

    <div class="flex items-center gap-4">
        <!-- Hamburger (Mobile Only) -->
        <button @click="sidebarOpen = !sidebarOpen" type="button"
            class="inline-flex items-center justify-center p-2 text-slate-400 rounded-md lg:hidden hover:text-slate-100 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary">
            <span class="sr-only">Open sidebar</span>
            <span class="material-symbols-outlined">menu</span>
        </button>

        <div class="flex-1 max-w-xl hidden sm:block w-64 md:w-96">
            <div class="relative group">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
                <input
                    class="w-full bg-slate-800 border-none text-slate-200 rounded-lg py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all placeholder-slate-500"
                    placeholder="Cari data anggaran, unit, atau laporan..." type="text" />
            </div>
        </div>
    </div>

    <div class="flex items-center gap-2 sm:gap-4">
        <button
            class="p-2 text-slate-500 hover:text-slate-300 hover:bg-slate-800 rounded-lg relative transition-colors">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-2 right-2 size-2 bg-red-500 rounded-full ring-2 ring-slate-900"></span>
        </button>

        <div class="h-8 w-px bg-slate-800 mx-1 sm:mx-2"></div>

        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <div
                    class="flex items-center gap-2 sm:gap-3 cursor-pointer hover:bg-slate-800 p-1 rounded-lg transition-colors text-slate-300">
                    <span class="text-sm font-medium hidden sm:block">Budget Year {{ date('Y') }}</span>
                    <span class="material-symbols-outlined text-slate-400">expand_more</span>
                </div>
            </x-slot>

            <x-slot name="content">
                <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-800">
                    <p class="text-sm text-slate-900 dark:text-slate-200 font-medium"
                        x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                        x-on:profile-updated.window="name = $event.detail.name"></p>
                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                </div>
                <x-dropdown-link :href="route('profile')" wire:navigate
                    class="!text-slate-700 dark:!text-slate-300 hover:!bg-slate-100 dark:hover:!bg-slate-800">
                    {{ __('Profile') }}
                </x-dropdown-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-dropdown-link
                        class="!text-slate-700 dark:!text-slate-300 hover:!bg-slate-100 dark:hover:!bg-slate-800">
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </button>
            </x-slot>
        </x-dropdown>
    </div>
</header>
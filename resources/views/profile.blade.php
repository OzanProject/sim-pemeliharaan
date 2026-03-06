@extends('backend.layouts.app')

@section('title', 'Profil Pengguna - ' . ($globalSettings['app_name'] ?? 'SIM Kendaraan'))

@section('content')
    <div class="space-y-6">
        <!-- Header Page -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Profil Saya</h2>
                <p class="text-slate-500 mt-1 text-sm">Kelola informasi akun dan pengaturan keamanan kata sandi Anda.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Informasi & Password -->
            <div class="lg:col-span-2 space-y-6">
                <div
                    class="p-6 bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm">
                    <div class="max-w-xl">
                        <livewire:profile.update-profile-information-form />
                    </div>
                </div>

                <div
                    class="p-6 bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm">
                    <div class="max-w-xl">
                        <livewire:profile.update-password-form />
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Danger Zone -->
            <div class="space-y-6">
                <div
                    class="p-6 bg-red-50/50 dark:bg-red-900/10 rounded-xl border border-red-100 dark:border-red-900/30 shadow-sm">
                    <div class="max-w-xl">
                        <livewire:profile.delete-user-form />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
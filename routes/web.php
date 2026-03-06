<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Livewire\Volt\Volt;

// Route untuk halaman depan (Landing Page awal)
Route::view('/', 'frontend.pages.home')->name('home');
Route::view('/layanan', 'frontend.pages.services')->name('services');
Route::view('/tentang-kami', 'frontend.pages.about')->name('about');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard Utama
    Volt::route('dashboard', 'dashboard')->name('dashboard');

    // Modul Master Data Kendaraan
    Volt::route('units', 'units.index')->name('units.index');
    Volt::route('budgets', 'budgets.index')->name('budgets.index');
    Volt::route('expenses', 'expenses.index')->name('expenses.index');
    Volt::route('reports', 'reports.index')->name('reports.index');
    Route::get('reports/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
    Route::post('notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.readAll');

    // Route khusus Super Admin
    Route::middleware(['admin'])->group(function () {
        Volt::route('users', 'users.index')->name('users.index');
        Volt::route('settings', 'settings.index')->name('settings.index');
        Volt::route('settings/frontend', 'settings.frontend')->name('settings.frontend');
    });
});

// Route untuk halaman Edit Profil bawaan Breeze
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// --- TAMBAHAN KITA: Route Manual untuk Logout ---
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');
// ------------------------------------------------

// Memanggil file route untuk sistem Login, Register, dll
require __DIR__ . '/auth.php';
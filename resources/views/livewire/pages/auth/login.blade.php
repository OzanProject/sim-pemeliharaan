<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;
    public bool $showPassword = false;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Header -->
    <div style="margin-bottom: 36px;">
        <h2 style="font-size: 26px; font-weight: 900; color: white; margin: 0 0 8px;">Selamat Datang 👋</h2>
        <p style="color: rgba(255,255,255,0.5); font-size: 14px; margin: 0;">Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div
            style="padding: 12px 16px; background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34,197,94,0.3); border-radius: 10px; color: #4ade80; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <span class="material-symbols-outlined" style="font-size: 18px;">check_circle</span>
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="login" style="display: flex; flex-direction: column; gap: 20px;">

        <!-- Email -->
        <div>
            <label class="auth-label">Alamat Email</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined auth-input-icon">mail</span>
                <input wire:model="form.email" id="email" type="email" class="auth-input"
                    placeholder="nama@instansi.go.id" required autofocus autocomplete="username">
            </div>
            @error('form.email')
                <p class="auth-error"><span class="material-symbols-outlined" style="font-size:14px">error</span>
                    {{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label class="auth-label">Kata Sandi</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined auth-input-icon">lock</span>
                <input wire:model="form.password" id="password" type="{{ $showPassword ? 'text' : 'password' }}"
                    class="auth-input" style="padding-right: 48px;" placeholder="Masukkan kata sandi" required
                    autocomplete="current-password">
                <button type="button" wire:click="$toggle('showPassword')"
                    style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: rgba(255,255,255,0.4); padding: 0;">
                    <span class="material-symbols-outlined" style="font-size: 20px;">
                        {{ $showPassword ? 'visibility_off' : 'visibility' }}
                    </span>
                </button>
            </div>
            @error('form.password')
                <p class="auth-error"><span class="material-symbols-outlined" style="font-size:14px">error</span>
                    {{ $message }}</p>
            @enderror
        </div>

        <!-- Remember + Forgot -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <label
                style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: rgba(255,255,255,0.6); font-size: 13px;">
                <input wire:model="form.remember" type="checkbox" id="remember"
                    style="width: 16px; height: 16px; border-radius: 4px; accent-color: #1145d4;">
                Ingat saya
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" wire:navigate class="auth-link" style="font-size: 13px;">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="auth-btn" wire:loading.attr="disabled" wire:loading.class="opacity-75">
            <span wire:loading.remove class="flex items-center justify-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 20px; vertical-align: middle;">login</span>
                Masuk Sekarang
            </span>
            <span wire:loading style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                <svg style="width:20px;height:20px;animation:spin 1s linear infinite" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" stroke="white" stroke-width="3" stroke-dasharray="31.4"
                        stroke-dashoffset="10" stroke-linecap="round" />
                </svg>
                Memproses...
            </span>
        </button>

    </form>

    <!-- Register link -->
    @if (Route::has('register'))
        <p style="text-align: center; color: rgba(255,255,255,0.4); font-size: 13px; margin-top: 24px;">
            Belum punya akun?
            <a href="{{ route('register') }}" wire:navigate class="auth-link">Daftar di sini</a>
        </p>
    @endif

    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</div>
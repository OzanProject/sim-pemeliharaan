<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $showPassword = false;
    public bool $showConfirmPassword = false;

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));
        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <h2 style="font-size: 26px; font-weight: 900; color: white; margin: 0 0 8px;">Buat Akun Baru ✨</h2>
        <p style="color: rgba(255,255,255,0.5); font-size: 14px; margin: 0;">Daftar untuk mengakses sistem manajemen</p>
    </div>

    <form wire:submit="register" style="display: flex; flex-direction: column; gap: 18px;">

        <!-- Name -->
        <div>
            <label class="auth-label">Nama Lengkap</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined auth-input-icon">person</span>
                <input wire:model="name" id="name" type="text" class="auth-input" placeholder="Nama sesuai identitas"
                    required autofocus autocomplete="name">
            </div>
            @error('name')
                <p class="auth-error"><span class="material-symbols-outlined" style="font-size:14px">error</span>
                    {{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label class="auth-label">Alamat Email</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined auth-input-icon">mail</span>
                <input wire:model="email" id="email" type="email" class="auth-input" placeholder="nama@instansi.go.id"
                    required autocomplete="username">
            </div>
            @error('email')
                <p class="auth-error"><span class="material-symbols-outlined" style="font-size:14px">error</span>
                    {{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label class="auth-label">Kata Sandi</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined auth-input-icon">lock</span>
                <input wire:model="password" id="password" type="{{ $showPassword ? 'text' : 'password' }}"
                    class="auth-input" style="padding-right: 48px;" placeholder="Min. 8 karakter" required
                    autocomplete="new-password">
                <button type="button" wire:click="$toggle('showPassword')"
                    style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: rgba(255,255,255,0.4); padding: 0;">
                    <span class="material-symbols-outlined" style="font-size: 20px;">
                        {{ $showPassword ? 'visibility_off' : 'visibility' }}
                    </span>
                </button>
            </div>
            @error('password')
                <p class="auth-error"><span class="material-symbols-outlined" style="font-size:14px">error</span>
                    {{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label class="auth-label">Konfirmasi Kata Sandi</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined auth-input-icon">lock_reset</span>
                <input wire:model="password_confirmation" type="{{ $showConfirmPassword ? 'text' : 'password' }}"
                    class="auth-input" style="padding-right: 48px;" placeholder="Ulangi kata sandi" required
                    autocomplete="new-password">
                <button type="button" wire:click="$toggle('showConfirmPassword')"
                    style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: rgba(255,255,255,0.4); padding: 0;">
                    <span class="material-symbols-outlined" style="font-size: 20px;">
                        {{ $showConfirmPassword ? 'visibility_off' : 'visibility' }}
                    </span>
                </button>
            </div>
            @error('password_confirmation')
                <p class="auth-error"><span class="material-symbols-outlined" style="font-size:14px">error</span>
                    {{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="auth-btn" wire:loading.attr="disabled" wire:loading.class="opacity-75"
            style="margin-top: 4px;">
            <span wire:loading.remove style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                <span class="material-symbols-outlined"
                    style="font-size: 20px; vertical-align: middle;">how_to_reg</span>
                Daftar Sekarang
            </span>
            <span wire:loading style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                <svg style="width:20px;height:20px;animation:spin 1s linear infinite" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" stroke="white" stroke-width="3" stroke-dasharray="31.4"
                        stroke-dashoffset="10" stroke-linecap="round" />
                </svg>
                Mendaftar...
            </span>
        </button>
    </form>

    <!-- Login link -->
    <p style="text-align: center; color: rgba(255,255,255,0.4); font-size: 13px; margin-top: 24px;">
        Sudah punya akun?
        <a href="{{ route('login') }}" wire:navigate class="auth-link">Masuk di sini</a>
    </p>

    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</div>
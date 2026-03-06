<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $showPassword = false;
    public bool $showConfirmPassword = false;

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = request()->string('email');
    }

    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            $this->addError('email', __($status));
            return;
        }

        Session::flash('status', __($status));
        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div
            style="width: 52px; height: 52px; border-radius: 16px; background: rgba(17,69,212,0.2); border: 1.5px solid rgba(17,69,212,0.4); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
            <span class="material-symbols-outlined" style="font-size: 26px; color: #818cf8;">password</span>
        </div>
        <h2 style="font-size: 26px; font-weight: 900; color: white; margin: 0 0 8px;">Buat Kata Sandi Baru</h2>
        <p style="color: rgba(255,255,255,0.5); font-size: 14px; margin: 0;">
            Masukkan kata sandi baru yang kuat dan mudah diingat.
        </p>
    </div>

    <form wire:submit="resetPassword" style="display: flex; flex-direction: column; gap: 20px;">

        <!-- Email (readonly) -->
        <div>
            <label class="auth-label">Email</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined auth-input-icon">mail</span>
                <input wire:model="email" id="email" type="email" class="auth-input"
                    style="opacity: 0.6; cursor: default;" readonly autocomplete="username">
            </div>
            @error('email')
                <p class="auth-error"><span class="material-symbols-outlined" style="font-size:14px">error</span>
                    {{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label class="auth-label">Kata Sandi Baru</label>
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
            <label class="auth-label">Konfirmasi Kata Sandi Baru</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined auth-input-icon">lock_reset</span>
                <input wire:model="password_confirmation" type="{{ $showConfirmPassword ? 'text' : 'password' }}"
                    class="auth-input" style="padding-right: 48px;" placeholder="Ulangi kata sandi baru" required
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
        <button type="submit" class="auth-btn" wire:loading.attr="disabled" wire:loading.class="opacity-75">
            <span wire:loading.remove style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                <span class="material-symbols-outlined"
                    style="font-size: 20px; vertical-align: middle;">check_circle</span>
                Simpan Kata Sandi Baru
            </span>
            <span wire:loading style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                <svg style="width:20px;height:20px;animation:spin 1s linear infinite" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" stroke="white" stroke-width="3" stroke-dasharray="31.4"
                        stroke-dashoffset="10" stroke-linecap="round" />
                </svg>
                Menyimpan...
            </span>
        </button>
    </form>

    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</div>
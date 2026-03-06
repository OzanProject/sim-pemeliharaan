<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate(['email' => ['required', 'string', 'email']]);

        $status = Password::sendResetLink($this->only('email'));

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));
            return;
        }

        $this->reset('email');
        session()->flash('status', __($status));
    }
}; ?>

<div>
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div
            style="width: 52px; height: 52px; border-radius: 16px; background: rgba(17,69,212,0.2); border: 1.5px solid rgba(17,69,212,0.4); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
            <span class="material-symbols-outlined" style="font-size: 26px; color: #818cf8;">lock_reset</span>
        </div>
        <h2 style="font-size: 26px; font-weight: 900; color: white; margin: 0 0 8px;">Lupa Kata Sandi?</h2>
        <p style="color: rgba(255,255,255,0.5); font-size: 14px; margin: 0; line-height: 1.6;">
            Tidak masalah. Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
        </p>
    </div>

    <!-- Status Message -->
    @if (session('status'))
        <div
            style="padding: 14px 16px; background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.3); border-radius: 12px; color: #4ade80; font-size: 13px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
            <span class="material-symbols-outlined" style="font-size: 20px; flex-shrink: 0;">mark_email_read</span>
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="sendPasswordResetLink" style="display: flex; flex-direction: column; gap: 20px;">

        <!-- Email -->
        <div>
            <label class="auth-label">Alamat Email Terdaftar</label>
            <div style="position: relative;">
                <span class="material-symbols-outlined auth-input-icon">mail</span>
                <input wire:model="email" id="email" type="email" class="auth-input" placeholder="nama@instansi.go.id"
                    required autofocus autocomplete="email">
            </div>
            @error('email')
                <p class="auth-error"><span class="material-symbols-outlined" style="font-size:14px">error</span>
                    {{ $message }}</p>
            @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="auth-btn" wire:loading.attr="disabled" wire:loading.class="opacity-75">
            <span wire:loading.remove style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                <span class="material-symbols-outlined" style="font-size: 20px; vertical-align: middle;">send</span>
                Kirim Tautan Reset
            </span>
            <span wire:loading style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                <svg style="width:20px;height:20px;animation:spin 1s linear infinite" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" stroke="white" stroke-width="3" stroke-dasharray="31.4"
                        stroke-dashoffset="10" stroke-linecap="round" />
                </svg>
                Mengirim...
            </span>
        </button>
    </form>

    <p style="text-align: center; color: rgba(255,255,255,0.4); font-size: 13px; margin-top: 28px;">
        Ingat kata sandI Anda?
        <a href="{{ route('login') }}" wire:navigate class="auth-link">Kembali masuk</a>
    </p>

    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</div>
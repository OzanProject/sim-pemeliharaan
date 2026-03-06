<?php

use App\Mail\OtpMail;
use App\Models\PasswordOtp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $email = '';
    public string $otp = '';
    public string $password = '';
    public string $password_confirmation = '';

    public int $step = 1; // 1: Email, 2: OTP, 3: New Password

    public function sendOtp(): void
    {
        $this->validate(['email' => ['required', 'string', 'email', 'exists:users,email']]);

        // Generate 6 digit OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Save to DB
        PasswordOtp::updateOrCreate(
            ['email' => $this->email],
            [
                'otp' => $otpCode,
                'expires_at' => now()->addMinutes(5),
            ]
        );

        // Send Email
        $appName = \App\Models\Setting::where('key', 'app_name')->first()?->value ?? 'SIM Kendaraan';
        try {
            Mail::to($this->email)->send(new OtpMail($otpCode, $appName));
            $this->step = 2;
            session()->flash('status', 'Kode OTP telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            $this->addError('email', 'Gagal mengirim email. Silakan cek konfigurasi SMTP Anda.');
        }
    }

    public function verifyOtp(): void
    {
        $this->validate(['otp' => ['required', 'string', 'size:6']]);

        $otpRecord = PasswordOtp::where('email', $this->email)
            ->where('otp', $this->otp)
            ->valid()
            ->first();

        if (!$otpRecord) {
            $this->addError('otp', 'Kode OTP tidak valid atau sudah kadaluarsa.');
            return;
        }

        $this->step = 3;
    }

    public function resetPassword(): void
    {
        $this->validate([
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $user = User::where('email', $this->email)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($this->password),
            ]);

            // Hapus OTP setelah sukses
            PasswordOtp::where('email', $this->email)->delete();

            session()->flash('status', 'Kata sandi berhasil diperbarui. Silakan login.');
            $this->redirectRoute('login', navigate: true);
        }
    }
}; ?>

<div x-data="{ showPassword: false }">
    <!-- Header -->
    <div style="margin-bottom: 32px;">
        <div
            style="width: 52px; height: 52px; border-radius: 16px; background: rgba(17,69,212,0.2); border: 1.5px solid rgba(17,69,212,0.4); display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
            <span class="material-symbols-outlined" style="font-size: 26px; color: #818cf8;">
                {{ $step == 1 ? 'lock_reset' : ($step == 2 ? 'mark_email_unread' : 'lock_open') }}
            </span>
        </div>
        <h2 style="font-size: 26px; font-weight: 900; color: white; margin: 0 0 8px;">
            {{ $step == 1 ? 'Lupa Kata Sandi?' : ($step == 2 ? 'Verifikasi OTP' : 'Kata Sandi Baru') }}
        </h2>
        <p style="color: rgba(255,255,255,0.5); font-size: 14px; margin: 0; line-height: 1.6;">
            @if($step == 1)
                Masukkan email terdaftar dan kami akan mengirimkan 6 digit kode verifikasi.
            @elseif($step == 2)
                Kami telah mengirimkan kode 6 digit ke <strong>{{ $email }}</strong>. Masukkan kode tersebut di bawah.
            @else
                Hampir selesai. Masukkan kata sandi baru untuk akun Anda.
            @endif
        </p>
    </div>

    <!-- Status Message -->
    @if (session('status'))
        <div
            style="padding: 14px 16px; background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.3); border-radius: 12px; color: #4ade80; font-size: 13px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
            <span class="material-symbols-outlined" style="font-size: 20px; flex-shrink: 0;">
                {{ session('status') == 'Kata sandi berhasil diperbarui. Silakan login.' ? 'check_circle' : 'mark_email_read' }}
            </span>
            {{ session('status') }}
        </div>
    @endif

    <!-- STEP 1: INPUT EMAIL -->
    @if($step == 1)
        <form wire:submit="sendOtp" style="display: flex; flex-direction: column; gap: 20px;">
            <div>
                <label class="auth-label">Alamat Email Terdaftar</label>
                <div style="position: relative;">
                    <span class="material-symbols-outlined auth-input-icon">mail</span>
                    <input wire:model="email" id="email" type="email" class="auth-input" placeholder="nama@instansi.go.id"
                        required autofocus>
                </div>
                @error('email')
                    <p class="auth-error"><span class="material-symbols-outlined" style="font-size:14px">error</span>
                        {{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="auth-btn" wire:loading.attr="disabled" wire:loading.class="opacity-75">
                <span wire:loading.remove style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <span class="material-symbols-outlined" style="font-size: 20px;">send</span>
                    Kirim Kode OTP
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
    @endif

    <!-- STEP 2: VERIFY OTP -->
    @if($step == 2)
        <form wire:submit="verifyOtp" style="display: flex; flex-direction: column; gap: 20px;">
            <div>
                <label class="auth-label">Masukkan 6 Digit OTP</label>
                <div style="position: relative;">
                    <span class="material-symbols-outlined auth-input-icon">pin</span>
                    <input wire:model="otp" id="otp" type="text" class="auth-input" placeholder="000000" maxlength="6"
                        required autofocus
                        style="letter-spacing: 8px; font-weight: bold; text-align: center; font-size: 18px;">
                </div>
                @error('otp')
                    <p class="auth-error"><span class="material-symbols-outlined" style="font-size:14px">error</span>
                        {{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="auth-btn" wire:loading.attr="disabled" wire:loading.class="opacity-75">
                <span wire:loading.remove style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <span class="material-symbols-outlined" style="font-size: 20px;">verified_user</span>
                    Verifikasi Kode
                </span>
                <span wire:loading style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <svg style="width:20px;height:20px;animation:spin 1s linear infinite" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="10" stroke="white" stroke-width="3" stroke-dasharray="31.4"
                            stroke-dashoffset="10" stroke-linecap="round" />
                    </svg>
                    Mengecek...
                </span>
            </button>

            <button type="button" wire:click="$set('step', 1)"
                style="background: transparent; border: none; color: rgba(255,255,255,0.4); cursor: pointer; font-size: 13px; text-decoration: underline;">
                Ganti Email
            </button>
        </form>
    @endif

    <!-- STEP 3: RESET PASSWORD -->
    @if($step == 3)
        <form wire:submit="resetPassword" style="display: flex; flex-direction: column; gap: 20px;">
            <!-- Password Baru -->
            <div>
                <label class="auth-label">Kata Sandi Baru</label>
                <div style="position: relative;">
                    <span class="material-symbols-outlined auth-input-icon">lock</span>
                    <input wire:model="password" :type="showPassword ? 'text' : 'password'" class="auth-input"
                        placeholder="••••••••" required>
                    <button type="button" @click="showPassword = !showPassword"
                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: rgba(255,255,255,0.3); cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <span class="material-symbols-outlined" style="font-size: 20px;"
                            x-text="showPassword ? 'visibility_off' : 'visibility'"></span>
                    </button>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div>
                <label class="auth-label">Konfirmasi Kata Sandi</label>
                <div style="position: relative;">
                    <span class="material-symbols-outlined auth-input-icon">lock_clock</span>
                    <input wire:model="password_confirmation" :type="showPassword ? 'text' : 'password'" class="auth-input"
                        placeholder="••••••••" required>
                </div>
                @error('password')
                    <p class="auth-error"><span class="material-symbols-outlined" style="font-size:14px">error</span>
                        {{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="auth-btn" wire:loading.attr="disabled" wire:loading.class="opacity-75">
                <span wire:loading.remove style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <span class="material-symbols-outlined" style="font-size: 20px;">update</span>
                    Perbarui Kata Sandi
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
    @endif

    <p style="text-align: center; color: rgba(255,255,255,0.4); font-size: 13px; margin-top: 28px;">
        Ingat kata sandi Anda?
        <a href="{{ route('login') }}" wire:navigate class="auth-link">Kembali masuk</a>
    </p>

    <style>
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .auth-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 8px;
            padding-left: 2px;
        }

        .auth-input {
            width: 100%;
            background: rgba(255, 255, 255, 0.04);
            border: 1.5px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 12px 16px 12px 44px;
            color: white;
            font-size: 14px;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .auth-input:focus {
            outline: none;
            background: rgba(129, 140, 248, 0.06);
            border-color: #818cf8;
            box-shadow: 0 0 0 4px rgba(129, 140, 248, 0.15);
        }

        .auth-input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.3);
            font-size: 20px;
        }

        .auth-btn {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            background: #818cf8;
            color: white;
            font-weight: 700;
            font-size: 15px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .auth-btn:hover {
            background: #6366f1;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .auth-error {
            color: #ef4444;
            font-size: 12px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            padding-left: 2px;
        }

        .auth-link {
            color: #818cf8;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-link:hover {
            text-decoration: underline;
        }
    </style>
</div>
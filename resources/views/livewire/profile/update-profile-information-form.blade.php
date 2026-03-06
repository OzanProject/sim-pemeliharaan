<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public $avatarUpload;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($this->avatarUpload) {
            $path = $this->avatarUpload->store('avatars', 'public');
            $user->avatar = '/storage/' . $path;
        }

        $user->save();

        // Optional: clear upload file after success
        $this->avatarUpload = null;

        $this->dispatch('swal', title: 'Profil berhasil diperbarui.', icon: 'success');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-slate-900 dark:text-slate-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <!-- Avatar Upload Section -->
        <div>
            <x-input-label value="Foto Profil (Avatar)" />
            <div class="mt-2 flex items-center gap-6">
                <!-- Preview Bulat -->
                <div
                    class="h-20 w-20 shrink-0 relative rounded-full overflow-hidden bg-slate-100 border border-slate-200 dark:bg-slate-800 dark:border-slate-700">
                    @if ($avatarUpload)
                        <img src="{{ $avatarUpload->temporaryUrl() }}" class="h-full w-full object-cover rounded-full" />
                    @elseif (auth()->user()->avatar)
                        <img src="{{ asset(auth()->user()->avatar) }}" class="h-full w-full object-cover rounded-full" />
                    @else
                        <div class="h-full w-full flex items-center justify-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl">person</span>
                        </div>
                    @endif
                    <div wire:loading wire:target="avatarUpload"
                        class="absolute inset-0 bg-slate-900/50 flex items-center justify-center text-white">
                        <span class="material-symbols-outlined animate-spin">sync</span>
                    </div>
                </div>

                <!-- File Input -->
                <div class="flex-1">
                    <input type="file" wire:model="avatarUpload" accept="image/*"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Format disarankan: JPG, PNG rasio persegi
                        (1:1).</p>
                    <x-input-error class="mt-2" :messages="$errors->get('avatarUpload')" />
                </div>
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required
                autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" name="email" type="email" class="mt-1 block w-full" required
                autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button wire:click.prevent="sendVerification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
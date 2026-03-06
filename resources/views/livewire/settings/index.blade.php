<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\Setting;
use Livewire\WithFileUploads;

new #[Layout('backend.layouts.app')] class extends Component {
    use WithFileUploads;

    public $settings = [];
    public $logoUpload; // khusus untuk unggahan logo

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $allSettings = Setting::orderBy('order_column')->get();
        foreach ($allSettings as $setting) {
            $this->settings[$setting->key] = $setting->value;
        }
    }

    public function updateSettings()
    {
        // Update general text settings
        foreach ($this->settings as $key => $value) {
            if ($key !== 'app_logo' && $value !== null) {
                Setting::where('key', $key)->update(['value' => $value]);
            }
        }

        // Handle Image Upload for Logo
        if ($this->logoUpload) {
            $path = $this->logoUpload->store('settings', 'public');
            Setting::where('key', 'app_logo')->update(['value' => $path]);
            $this->settings['app_logo'] = $path;
            $this->logoUpload = null; // reset file input
        }

        $this->dispatch('swal', title: 'Pengaturan berhasil diperbarui!', icon: 'success');
    }
    
    // helper to get DB items layout
    public function getSettingsByGroup($group) {
        return Setting::where('group', $group)->orderBy('order_column')->get();
    }
    public string $testEmail = '';

    public function sendTestEmail()
    {
        $this->validate(['testEmail' => ['required', 'email']]);
        try {
            \Illuminate\Support\Facades\Mail::raw('Ini adalah email uji coba dari ' . ($this->settings['app_name'] ?? 'SIM Kendaraan') . '. Konfigurasi SMTP Anda berhasil terhubung!', function ($m) {
                $m->to($this->testEmail)->subject('Uji Koneksi Email - ' . ($this->settings['app_name'] ?? 'SIM Kendaraan'));
            });
            $this->dispatch('swal', title: 'Email berhasil dikirim ke ' . $this->testEmail, icon: 'success');
        } catch (\Exception $e) {
            $this->dispatch('swal', title: 'Gagal mengirim email: ' . $e->getMessage(), icon: 'error');
        }
        $this->testEmail = '';
    }
}; ?>

<div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Pengaturan Umum</h2>
            <p class="text-slate-500 mt-1 text-sm">Konfigurasi data aplikasi dasar dan informasi instansi.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <form wire:submit="updateSettings">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
                <!-- Kolom Kiri: General Settings -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-2">Aplikasi</h3>
                    
                    @foreach($this->getSettingsByGroup('general') as $setting)
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ $setting->display_name }}</label>
                            
                            @if($setting->type == 'text')
                                <input type="text" wire:model.defer="settings.{{ $setting->key }}" 
                                    class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                            
                            @elseif($setting->type == 'image')
                                <div class="flex items-center gap-4 mt-2">
                                    @if(isset($settings[$setting->key]) && $settings[$setting->key])
                                        <div class="w-16 h-16 rounded border border-slate-200 overflow-hidden flex items-center justify-center bg-slate-50">
                                            <img src="{{ asset('storage/' . ltrim($settings[$setting->key], '/')) }}" alt="Logo" class="max-w-full max-h-full object-contain">
                                        </div>
                                    @else
                                        <div class="w-16 h-16 rounded border border-slate-200 border-dashed flex items-center justify-center bg-slate-50 dark:bg-slate-800">
                                            <span class="text-xs text-slate-400">Kosong</span>
                                        </div>
                                    @endif
                                    
                                    <input type="file" wire:model="logoUpload" accept="image/*"
                                        class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                                </div>
                                <div wire:loading wire:target="logoUpload" class="text-xs text-emerald-500 mt-1">Mengunggah...</div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Kolom Kanan: Agency Settings -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800 pb-2">Informasi Instansi</h3>
                    
                    @foreach($this->getSettingsByGroup('agency') as $setting)
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ $setting->display_name }}</label>
                            
                            @if($setting->type == 'text')
                                @if($setting->key == 'agency_address')
                                    <textarea wire:model.defer="settings.{{ $setting->key }}" rows="3"
                                        class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200"></textarea>
                                @else
                                    <input type="text" wire:model.defer="settings.{{ $setting->key }}" 
                                        class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- SMTP Section -->
            <div class="border-t border-slate-100 dark:border-slate-800 p-6">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200 pb-2 mb-5">Konfigurasi Email (SMTP)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($this->getSettingsByGroup('mail') as $setting)
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ $setting->display_name }}</label>
                            @if($setting->type == 'password')
                                <input type="password" wire:model.defer="settings.{{ $setting->key }}"
                                    placeholder="••••••••"
                                    class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                            @else
                                <input type="text" wire:model.defer="settings.{{ $setting->key }}"
                                    class="w-full rounded-lg border-slate-200 focus:border-primary focus:ring-primary dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Test Email Form -->
                <div class="mt-6 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800">
                    <p class="text-sm font-semibold text-blue-700 dark:text-blue-300 mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">send</span>
                        Uji Koneksi Email
                    </p>
                    <div class="flex gap-3 flex-col sm:flex-row">
                        <input type="email" wire:model.defer="testEmail" placeholder="Masukkan email tujuan uji..."
                            class="flex-1 rounded-lg border-blue-200 focus:border-primary focus:ring-primary dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 text-sm">
                        <button type="button" wire:click="sendTestEmail"
                            wire:loading.attr="disabled" wire:loading.class="opacity-75"
                            class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors flex items-center gap-2 whitespace-nowrap">
                            <span wire:loading.remove wire:target="sendTestEmail" class="material-symbols-outlined text-[16px]">mark_email_read</span>
                            <span wire:loading wire:target="sendTestEmail" class="material-symbols-outlined text-[16px] animate-spin">sync</span>
                            Kirim Email Uji
                        </button>
                    </div>
                    @error('testEmail') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Footer / Action Buttons -->
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-indigo-600 shadow-sm transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

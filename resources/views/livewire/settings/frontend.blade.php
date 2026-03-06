<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\FrontendSetting;

new #[Layout('backend.layouts.app')] class extends Component {
    use WithFileUploads;

    public $settings = [];
    public $heroUpload;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $data = FrontendSetting::all();
        foreach ($data as $item) {
            $this->settings[$item->key] = $item->value;
        }
    }

    public function save()
    {
        if ($this->heroUpload) {
            $path = $this->heroUpload->store('frontends', 'public');
            $this->settings['hero_image'] = '/storage/' . $path;
        }

        foreach ($this->settings as $key => $value) {
            FrontendSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $this->dispatch('swal', title: 'Pengaturan Halaman Depan berhasil diperbarui.', icon: 'success');
    }
}; ?>

<div x-data="{ activeTab: 'hero' }" class="space-y-6">
    <!-- Header Page & Flash Message -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Pengaturan Web Publik</h2>
            <p class="text-slate-500 mt-1 text-sm">Ubah teks dan konten Halaman Depan (Landing Page) instansi Anda tanpa
                proses *coding*.</p>
        </div>
    </div>

    <div
        class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
        <!-- Tabs Navigasi -->
        <div class="flex overflow-x-auto border-b border-slate-100 dark:border-slate-800">
            <button @click="activeTab = 'hero'"
                :class="{ 'border-primary text-primary font-bold': activeTab === 'hero', 'border-transparent text-slate-500 hover:text-slate-700': activeTab !== 'hero' }"
                class="px-6 py-4 text-sm whitespace-nowrap border-b-2 transition-colors focus:outline-none">
                Beranda & Hero
            </button>
            <button @click="activeTab = 'features'"
                :class="{ 'border-primary text-primary font-bold': activeTab === 'features', 'border-transparent text-slate-500 hover:text-slate-700': activeTab !== 'features' }"
                class="px-6 py-4 text-sm whitespace-nowrap border-b-2 transition-colors focus:outline-none">
                Layanan Utama
            </button>
            <button @click="activeTab = 'about'"
                :class="{ 'border-primary text-primary font-bold': activeTab === 'about', 'border-transparent text-slate-500 hover:text-slate-700': activeTab !== 'about' }"
                class="px-6 py-4 text-sm whitespace-nowrap border-b-2 transition-colors focus:outline-none">
                Informasi & Sosial Media
            </button>
        </div>

        <!-- Form Konten utama -->
        <form wire:submit.prevent="save" class="p-6 md:p-8 space-y-8">

            <!-- TAB 1: HERO -->
            <div x-show="activeTab === 'hero'" class="space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">Seksi Banner (Hero)</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul
                                Utama</label>
                            <input type="text" wire:model="settings.hero_title"
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                            <p class="text-xs text-slate-500 mt-1">Format HTML &lt;span class="text-primary"&gt;
                                diperbolehkan.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sub Judul /
                                Deskripsi</label>
                            <textarea wire:model="settings.hero_subtitle" rows="3"
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Gambar Banner Utama</label>
                            
                            <!-- Image Preview -->
                            <div class="mb-3 relative w-full h-48 sm:h-64 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 group">
                                @if ($heroUpload)
                                    <img src="{{ $heroUpload->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif(isset($settings['hero_image']) && $settings['hero_image'])
                                    <img src="{{ Str::startsWith($settings['hero_image'], ['http://', 'https://']) ? $settings['hero_image'] : asset($settings['hero_image']) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center w-full h-full text-slate-400">Belum ada gambar</div>
                                @endif
                                
                                <div wire:loading wire:target="heroUpload" class="absolute inset-0 bg-slate-900/50 flex flex-col items-center justify-center text-white backdrop-blur-sm">
                                    <span class="material-symbols-outlined animate-spin text-3xl mb-2">sync</span>
                                    <span class="text-sm font-medium">Mengunggah...</span>
                                </div>
                            </div>

                            <input type="file" wire:model="heroUpload" accept="image/*"
                                class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                            <p class="text-xs text-slate-500 mt-2">Disarankan menggunakan gambar lanskap (16:9) resolusi tinggi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: FEATURES -->
            <div x-show="activeTab === 'features'" class="space-y-6" style="display: none;">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">Pengantar Layanan Utama</h3>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul Seksi
                                Fitur</label>
                            <input type="text" wire:model="settings.feature_heading"
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi
                                Pendek Seksi Fitur</label>
                            <textarea wire:model="settings.feature_subheading" rows="2"
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"></textarea>
                        </div>
                    </div>
                    <hr class="my-6 border-slate-100 dark:border-slate-800">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">Item Layanan 3 Kolom</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Fitur 1 -->
                        <div class="space-y-4">
                            <div
                                class="px-3 py-1 bg-primary/10 text-primary font-bold text-xs rounded-full inline-block">
                                Kolom 1</div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul
                                    Layanan 1</label>
                                <input type="text" wire:model="settings.feature_1_title"
                                    class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi
                                    Layanan 1</label>
                                <textarea wire:model="settings.feature_1_desc" rows="3"
                                    class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"></textarea>
                            </div>
                        </div>
                        <!-- Fitur 2 -->
                        <div class="space-y-4">
                            <div
                                class="px-3 py-1 bg-primary/10 text-primary font-bold text-xs rounded-full inline-block">
                                Kolom 2</div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul
                                    Layanan 2</label>
                                <input type="text" wire:model="settings.feature_2_title"
                                    class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi
                                    Layanan 2</label>
                                <textarea wire:model="settings.feature_2_desc" rows="3"
                                    class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"></textarea>
                            </div>
                        </div>
                        <!-- Fitur 3 -->
                        <div class="space-y-4">
                            <div
                                class="px-3 py-1 bg-primary/10 text-primary font-bold text-xs rounded-full inline-block">
                                Kolom 3</div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul
                                    Layanan 3</label>
                                <input type="text" wire:model="settings.feature_3_title"
                                    class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi
                                    Layanan 3</label>
                                <textarea wire:model="settings.feature_3_desc" rows="3"
                                    class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: ABOUT & CONTACT -->
            <div x-show="activeTab === 'about'" class="space-y-8" style="display: none;">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">Tentang Aplikasi (Halaman
                        Profil)</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul
                                Deskripsi Utama</label>
                            <input type="text" wire:model="settings.about_title"
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Konten
                                Profil (Narasi Panjang)</label>
                            <textarea wire:model="settings.about_content" rows="4"
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Deskripsi
                                Pendek Footer</label>
                            <textarea wire:model="settings.company_description" rows="2"
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"></textarea>
                        </div>
                    </div>
                </div>
                <hr class="border-slate-100 dark:border-slate-800">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4">Kontak & Sosial Media</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Alamat
                                Lengkap (Kop Bawah)</label>
                            <input type="text" wire:model="settings.contact_address"
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email
                                Publik (Customer Service)</label>
                            <input type="email" wire:model="settings.contact_email"
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">URL Website
                                Utama</label>
                            <input type="url" wire:model="settings.social_web"
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">URL
                                Instagram</label>
                            <input type="text" wire:model="settings.social_instagram"
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit"
                    class="flex items-center gap-2 px-6 py-2.5 bg-primary text-white font-bold rounded-lg hover:bg-indigo-600 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Simpan Perubahan Teks
                </button>
            </div>
        </form>
    </div>
</div>
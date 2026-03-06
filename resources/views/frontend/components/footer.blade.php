<footer class="bg-white dark:bg-background-dark border-t border-slate-200 dark:border-slate-800 pt-16 pb-8">
  <div class="max-w-[1280px] mx-auto px-6 md:px-10">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
      <div class="flex flex-col gap-4">
        <a href="{{ url('/') }}" class="flex items-center gap-2">
          @if(!empty($globalSettings['app_logo']))
            <img src="{{ asset('storage/' . ltrim($globalSettings['app_logo'], '/')) }}" alt="Logo"
              class="w-6 h-6 rounded-lg object-contain bg-white">
          @else
            <div class="text-primary size-6">
              <svg fill="currentColor" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M24 4C25.7818 14.2173 33.7827 22.2182 44 24C33.7827 25.7818 25.7818 33.7827 24 44C22.2182 33.7827 14.2173 25.7818 4 24C14.2173 22.2182 22.2182 14.2173 24 4Z">
                </path>
              </svg>
            </div>
          @endif
          <span
            class="font-bold text-xl text-slate-900 dark:text-white">{{ $globalSettings['app_name'] ?? 'SIM Kendaraan' }}</span>
        </a>
        <p class="text-slate-500 text-sm leading-relaxed">
          {{ $frontendSettings['company_description'] ?? 'Sistem Informasi Manajemen Kendaraan Profesional.' }}
        </p>
      </div>
      <div>
        <h5 class="text-slate-900 dark:text-white font-bold mb-4">Sistem</h5>
        <ul class="flex flex-col gap-2 text-sm text-slate-600 dark:text-slate-400">
          <li><a class="hover:text-primary transition-colors" href="{{ url('/tentang-kami') }}">Tentang</a></li>
          <li><a class="hover:text-primary transition-colors" href="#">Kebijakan Privasi</a></li>
          <li><a class="hover:text-primary transition-colors" href="#">Panduan Penggunaan</a></li>
        </ul>
      </div>
      <div>
        <h5 class="text-slate-900 dark:text-white font-bold mb-4">Layanan</h5>
        <ul class="flex flex-col gap-2 text-sm text-slate-600 dark:text-slate-400">
          <li><a class="hover:text-primary transition-colors" href="{{ url('/layanan') }}">Semua Layanan</a></li>
          <li><a class="hover:text-primary transition-colors" href="#">Infrastruktur Cloud</a></li>
          <li><a class="hover:text-primary transition-colors" href="#">Dukungan Teknis</a></li>
        </ul>
      </div>
      <div>
        <h5 class="text-slate-900 dark:text-white font-bold mb-4">Kontak</h5>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">
          {{ $frontendSettings['contact_address'] ?? 'Jl. Merdeka No 123' }}</p>
        <p class="text-sm text-slate-600 dark:text-slate-400">{{ $frontendSettings['contact_email'] ??
          'support@domain.com' }}</p>
      </div>
    </div>
    <div
      class="border-t border-slate-100 dark:border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
      <p class="text-xs text-slate-400">© {{ date('Y') }} {{ $globalSettings['app_name'] ?? 'SIM Kendaraan' }}. All
        rights reserved.</p>
      <div class="flex gap-6">
        <a class="text-slate-400 hover:text-primary transition-colors"
          href="{{ $frontendSettings['social_web'] ?? '#' }}" target="_blank">
          <span class="material-symbols-outlined text-xl">language</span>
        </a>
        <a class="text-slate-400 hover:text-primary transition-colors"
          href="{{ $frontendSettings['social_instagram'] ?? '#' }}" target="_blank">
          <span class="material-symbols-outlined text-xl">camera_alt</span>
        </a>
      </div>
    </div>
  </div>
</footer>
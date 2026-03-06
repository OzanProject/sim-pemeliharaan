@extends('frontend.layouts.app')

@section('content')
  <!-- Hero Section -->
  <div class="w-full max-w-[1280px]">
    <div class="flex flex-col gap-10 px-6 py-16 md:py-24 md:flex-row-reverse md:items-center">
      <div
        class="w-full aspect-video md:aspect-square bg-slate-200 dark:bg-slate-800 rounded-xl overflow-hidden shadow-2xl relative"
        style="background-image: url('{{ isset($frontendSettings['hero_image']) && Str::startsWith($frontendSettings['hero_image'], ['http://', 'https://']) ? $frontendSettings['hero_image'] : asset($frontendSettings['hero_image'] ?? '') }}'); background-size: cover; background-position: center;">
        <div class="absolute inset-0 bg-black/20 dark:bg-black/40"></div>
      </div>
      <div class="flex flex-col gap-8 md:w-full z-10">
        <div class="flex flex-col gap-4">
          <h1 class="text-slate-900 dark:text-white text-4xl font-black leading-[1.1] tracking-tight md:text-6xl">
            {!! $frontendSettings['hero_title'] ?? 'Akselerasi Performa Instansi Anda' !!}
          </h1>
          <p class="text-slate-600 dark:text-slate-400 text-lg font-normal leading-relaxed max-w-[600px]">
            {{ $frontendSettings['hero_subtitle'] ?? '-' }}
          </p>
        </div>
        <div class="flex flex-wrap gap-4">
          <a href="{{ route('login') }}"
            class="flex min-w-[180px] cursor-pointer items-center justify-center rounded-lg h-14 px-8 bg-primary text-white text-base font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
            Mulai Sekarang
          </a>
          <a href="{{ url('/layanan') }}"
            class="flex min-w-[180px] cursor-pointer items-center justify-center rounded-lg h-14 px-8 border-2 border-slate-200 dark:border-slate-800 bg-transparent text-slate-900 dark:text-white text-base font-bold hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all">
            Lihat Fitur
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Features Section -->
  <section class="w-full bg-white dark:bg-background-dark/50 py-20">
    <div class="max-w-[1280px] mx-auto px-6">
      <div class="flex flex-col gap-4 mb-12">
        <span class="text-primary font-bold tracking-widest uppercase text-xs">Innovation Focused</span>
        <h2 class="text-slate-900 dark:text-white text-3xl font-bold md:text-4xl tracking-tight">
          {{ $frontendSettings['feature_heading'] ?? 'Fitur Utama' }}
        </h2>
        <p class="text-slate-600 dark:text-slate-400 text-base max-w-[640px]">
          {{ $frontendSettings['feature_subheading'] ?? '-' }}
        </p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Feature 1 -->
        <div
          class="flex flex-col gap-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-8 hover:border-primary/50 transition-colors">
          <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
            <span class="material-symbols-outlined text-3xl">insights</span>
          </div>
          <div class="flex flex-col gap-2">
            <h3 class="text-slate-900 dark:text-white text-xl font-bold">
              {{ $frontendSettings['feature_1_title'] ?? 'Title 1' }}
            </h3>
            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
              {{ $frontendSettings['feature_1_desc'] ?? 'Desc 1' }}
            </p>
          </div>
        </div>
        <!-- Feature 2 -->
        <div
          class="flex flex-col gap-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-8 hover:border-primary/50 transition-colors">
          <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
            <span class="material-symbols-outlined text-3xl">analytics</span>
          </div>
          <div class="flex flex-col gap-2">
            <h3 class="text-slate-900 dark:text-white text-xl font-bold">
              {{ $frontendSettings['feature_2_title'] ?? 'Title 2' }}
            </h3>
            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
              {{ $frontendSettings['feature_2_desc'] ?? 'Desc 2' }}
            </p>
          </div>
        </div>
        <!-- Feature 3 -->
        <div
          class="flex flex-col gap-5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50 p-8 hover:border-primary/50 transition-colors">
          <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
            <span class="material-symbols-outlined text-3xl">groups</span>
          </div>
          <div class="flex flex-col gap-2">
            <h3 class="text-slate-900 dark:text-white text-xl font-bold">
              {{ $frontendSettings['feature_3_title'] ?? 'Title 3' }}
            </h3>
            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
              {{ $frontendSettings['feature_3_desc'] ?? 'Desc 3' }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section class="w-full py-20">
    <div class="max-w-[1000px] mx-auto px-6">
      <div class="text-center mb-16">
        <h2 class="text-slate-900 dark:text-white text-3xl font-bold md:text-4xl tracking-tight mb-4">
          Cara Kerja
        </h2>
        <p class="text-slate-600 dark:text-slate-400">Proses sederhana menggunakan Sistem Informasi Manajemen
          Kendaraan.</p>
      </div>
      <div class="relative">
        <!-- Connection Line (Desktop) -->
        <div
          class="hidden md:block absolute left-1/2 top-0 bottom-0 w-0.5 bg-slate-200 dark:bg-slate-800 -translate-x-1/2 -z-10">
        </div>
        <!-- Step 1 -->
        <div class="grid grid-cols-[48px_1fr] md:grid-cols-[1fr_48px_1fr] gap-6 md:gap-12 items-center mb-12">
          <div class="hidden md:block text-right">
            <h4 class="text-slate-900 dark:text-white text-lg font-bold">Registrasi Kendaraan
            </h4>
            <p class="text-slate-600 dark:text-slate-400 text-sm">Operator mendaftarkan detail armada dinas
              dan pagu anggarannya untuk tiap tahun anggaran.</p>
          </div>
          <div class="flex flex-col items-center">
            <div
              class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold z-10 shadow-lg shadow-primary/30">
              1</div>
          </div>
          <div class="md:hidden">
            <h4 class="text-slate-900 dark:text-white text-lg font-bold">Registrasi Kendaraan
            </h4>
            <p class="text-slate-600 dark:text-slate-400 text-sm">Operator mendaftarkan detail armada dinas
              dan pagu anggarannya untuk tiap tahun anggaran.</p>
          </div>
          <div class="hidden md:block"></div>
        </div>
        <!-- Step 2 -->
        <div class="grid grid-cols-[48px_1fr] md:grid-cols-[1fr_48px_1fr] gap-6 md:gap-12 items-center mb-12">
          <div class="hidden md:block"></div>
          <div class="flex flex-col items-center">
            <div
              class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold z-10 shadow-lg shadow-primary/30">
              2</div>
          </div>
          <div>
            <h4 class="text-slate-900 dark:text-white text-lg font-bold">Pencatatan Belanja
            </h4>
            <p class="text-slate-600 dark:text-slate-400 text-sm">Biaya BBM dan pemeliharaan servis
              langsung dicatatkan oleh pihak operasional setiap hari.</p>
          </div>
        </div>
        <!-- Step 3 -->
        <div class="grid grid-cols-[48px_1fr] md:grid-cols-[1fr_48px_1fr] gap-6 md:gap-12 items-center">
          <div class="hidden md:block text-right">
            <h4 class="text-slate-900 dark:text-white text-lg font-bold">Pelaporan Akurat</h4>
            <p class="text-slate-600 dark:text-slate-400 text-sm">Laporan bisa dipantau dan diekspor oleh
              Pimpinan secara mandiri dan cepat.</p>
          </div>
          <div class="flex flex-col items-center">
            <div
              class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold z-10 shadow-lg shadow-primary/30">
              3</div>
          </div>
          <div class="md:hidden">
            <h4 class="text-slate-900 dark:text-white text-lg font-bold">Pelaporan Akurat</h4>
            <p class="text-slate-600 dark:text-slate-400 text-sm">Laporan bisa dipantau dan diekspor oleh
              Pimpinan secara mandiri dan cepat.</p>
          </div>
          <div class="hidden md:block"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="w-full py-16 px-6">
    <div
      class="max-w-[1280px] mx-auto rounded-3xl bg-primary p-8 md:p-20 text-center text-white overflow-hidden relative">
      <!-- Abstract pattern background -->
      <div class="absolute inset-0 opacity-10 pointer-events-none"
        style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;">
      </div>
      <div class="relative z-10 max-w-[800px] mx-auto flex flex-col items-center gap-8">
        <h2 class="text-3xl md:text-5xl font-black tracking-tight">Siap Untuk Beralih Secara Digital?</h2>
        <p class="text-primary-100/90 text-lg">Kelola dan awasi efisiensi budget instansi Anda bersama
          {{ $globalSettings['app_name'] ?? 'SIM Kendaraan' }}.
        </p>
        <a href="{{ route('login') }}"
          class="flex min-w-[200px] cursor-pointer items-center justify-center rounded-lg h-14 px-10 bg-white text-primary text-base font-bold shadow-xl hover:bg-slate-50 active:scale-95 transition-all">
          Masuk ke Aplikasi
        </a>
      </div>
    </div>
  </section>
@endsection
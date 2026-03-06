@extends('frontend.layouts.app')

@section('title', 'Layanan Kami - ' . ($globalSettings['app_name'] ?? 'SIM Kendaraan'))

@section('content')
  <div class="w-full bg-slate-50 dark:bg-slate-900/50 pt-20 pb-32">
    <div class="max-w-[1000px] mx-auto px-6 text-center">
      <h1 class="text-slate-900 dark:text-white text-4xl md:text-5xl font-black mb-6">
        {{ $frontendSettings['services_title'] ?? 'Layanan Kami' }}
      </h1>
      <p class="text-slate-600 dark:text-slate-400 text-lg max-w-[700px] mx-auto leading-relaxed">
        {{ $frontendSettings['services_content'] ?? 'Solusi manajemen yang kami tawarkan.' }}
      </p>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-16 text-left">
        <div class="p-8 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
          <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center text-primary mb-6">
            <span class="material-symbols-outlined text-3xl">terminal</span>
          </div>
          <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Integrasi Sistem</h3>
          <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-sm">Penyelarasan data dari berbagai perangkat
            keras maupun aplikasi pelaporan untuk menjamin keterikatan informasi biaya dalam satu pintu dasbor.</p>
        </div>
        <div class="p-8 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700">
          <div class="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center text-primary mb-6">
            <span class="material-symbols-outlined text-3xl">support_agent</span>
          </div>
          <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Dukungan Teknis 24/7</h3>
          <p class="text-slate-600 dark:text-slate-400 leading-relaxed text-sm">Konsultasi ahli selalu mendampingi setiap
            tahap pengerjaan sistem operasional armada, termasuk asisten pemeliharaan infrastruktur cloud.</p>
        </div>
      </div>
    </div>
  </div>
@endsection
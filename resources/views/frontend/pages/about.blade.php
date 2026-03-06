@extends('frontend.layouts.app')

@section('title', 'Tentang Kami - ' . ($globalSettings['app_name'] ?? 'SIM Kendaraan'))

@section('content')
  <div class="w-full bg-white dark:bg-background-dark py-20">
    <div class="max-w-[1000px] mx-auto px-6">
      <div class="flex flex-col md:flex-row gap-12 items-center">
        <div class="w-full md:w-1/2 aspect-square rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 relative">
          <div class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('{{ $frontendSettings['hero_image'] ?? 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2070&auto=format&fit=crop' }}'); opacity: 0.9;">
          </div>
        </div>
        <div class="w-full md:w-1/2">
          <span class="text-primary font-bold tracking-widest uppercase text-xs">Mengenal Kami</span>
          <h1 class="text-slate-900 dark:text-white text-4xl font-black mt-3 mb-6">
            {{ $frontendSettings['about_title'] ?? 'Tentang Instansi' }}
          </h1>
          <div class="prose prose-slate dark:prose-invert prose-lg text-slate-600 dark:text-slate-400 leading-relaxed">
            <p>{{ $frontendSettings['about_content'] ?? 'Deskripsi tentang sejarah perusahaan.' }}</p>
            <p class="mt-4">Keberadaan sistem digital ini adalah sebuah wujud komitmen atas pelaporan pengeluaran dana
              operasional negara atau perusahaan yang dapat dipantau dari masa ke masa melalui kemudahan
              *Point-and-Click*.</p>
          </div>

          <div class="mt-10 flex items-center gap-4">
            <a href="{{ route('login') }}"
              class="px-6 py-3 bg-primary text-white text-sm font-bold rounded-lg shadow-md hover:bg-indigo-600 transition-colors">
              Mulai Kolaborasi
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
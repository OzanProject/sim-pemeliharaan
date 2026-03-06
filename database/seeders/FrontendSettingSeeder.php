<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\FrontendSetting;

class FrontendSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Hero Section
            [
                'key' => 'hero_title',
                'value' => 'Sistem Informasi Manajemen <span class="text-primary">Pemeliharaan Kendaraan Dinas</span>',
                'type' => 'text'
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'Pantau, catat, dan kelola seluruh armada kendaraan dinas operasional dengan lebih efisien dan terstruktur dalam satu platform terpusat.',
                'type' => 'text'
            ],
            [
                'key' => 'hero_image',
                'value' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=2070&auto=format&fit=crop',
                'type' => 'text'
            ],

            // Features
            ['key' => 'feature_heading', 'value' => 'Fitur Utama Sistem', 'type' => 'text'],
            ['key' => 'feature_subheading', 'value' => 'Integrasi seluruh proses pengelolaan, dari alokasi dana pemeliharaan hingga rekapitulasi realisasi penggunaan dengan transparansi tinggi.', 'type' => 'textarea'],
            ['key' => 'feature_1_title', 'value' => 'Pemantauan Terpusat', 'type' => 'text'],
            ['key' => 'feature_1_desc', 'value' => 'Pantau aktivitas seluruh kendaraan dinas dari satu dasbor interaktif tanpa harus mengecek satu per satu kuitansi fisik pengeluaran.', 'type' => 'textarea'],
            ['key' => 'feature_2_title', 'value' => 'Laporan Real-time', 'type' => 'text'],
            ['key' => 'feature_2_desc', 'value' => 'Data realisasi anggaran secara instan bisa direkapitulasi, didownload dalam bentuk Excel maupun file PDF berstandar eksekutif.', 'type' => 'textarea'],
            ['key' => 'feature_3_title', 'value' => 'Kolaborasi Optimal', 'type' => 'text'],
            ['key' => 'feature_3_desc', 'value' => 'Setiap operator dapat melaporkan secara independen keuangannya dengan aman berkat autentikasi multi-user di dalam aplikasi.', 'type' => 'textarea'],

            // Footer / Contact
            ['key' => 'company_description', 'value' => 'Professional services for the modern enterprise. Kami mendigitalisasi cara Anda mengelola keuangan aset fisik.', 'type' => 'textarea'],
            ['key' => 'contact_address', 'value' => 'Jl. Merdeka No 123, Komp Pemda', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'support@sim-kendaraan.id', 'type' => 'text'],
            ['key' => 'social_web', 'value' => 'https://budgetpro.id', 'type' => 'link'],
            ['key' => 'social_instagram', 'value' => '#', 'type' => 'link'],

            // About Page
            ['key' => 'about_title', 'value' => 'Tentang Aplikasi Kami', 'type' => 'text'],
            ['key' => 'about_content', 'value' => 'Sistem Manajemen Kendaraan (SIM Kendaraan) dikembangkan untuk memberikan transparansi dan otomatisasi pengelolaan transportasi instansi kepemerintahan dan perusahaan swasta tingkat menengah hingga besar.', 'type' => 'textarea'],

            // Services Page
            ['key' => 'services_title', 'value' => 'Layanan Utama', 'type' => 'text'],
            ['key' => 'services_content', 'value' => 'Kami melayani mulai dari pengadaan lisensi produk, dukungan teknis infrastruktur cloud 24/7, hingga konsultasi implementasi digitalisasi logistik di lapangan.', 'type' => 'textarea'],
        ];

        foreach ($settings as $setting) {
            FrontendSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}

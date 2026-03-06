<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'app_name',
                'display_name' => 'Nama Aplikasi',
                'value' => 'SIM Pemeliharaan Kendaraan Dinas',
                'type' => 'text',
                'group' => 'general',
                'order_column' => 1,
            ],
            [
                'key' => 'app_logo',
                'display_name' => 'Logo Aplikasi',
                'value' => null, // path ke gambar jika diupload
                'type' => 'image',
                'group' => 'general',
                'order_column' => 2,
            ],
            [
                'key' => 'report_title',
                'display_name' => 'Judul Cetak Laporan Utama',
                'value' => 'LAPORAN REALISASI ANGGARAN KENDARAAN DINAS',
                'type' => 'text',
                'group' => 'general',
                'order_column' => 3,
            ],
            [
                'key' => 'agency_name',
                'display_name' => 'Nama Instansi',
                'value' => 'Pemerintah Daerah (Contoh)',
                'type' => 'text',
                'group' => 'agency',
                'order_column' => 4,
            ],
            [
                'key' => 'agency_address',
                'display_name' => 'Alamat Instansi',
                'value' => 'Jl. Komplek Pemda No. 123, Kabupaten X',
                'type' => 'text', // bisa juga textarea, disimplifikasi sebagai text panjang
                'group' => 'agency',
                'order_column' => 5,
            ],
            [
                'key' => 'agency_contact',
                'display_name' => 'Kontak Instansi (Telp/Email)',
                'value' => '021-1234567 | kontak@pemda.go.id',
                'type' => 'text',
                'group' => 'agency',
                'order_column' => 6,
            ],
            ['key' => 'mail_host', 'display_name' => 'SMTP Host', 'value' => 'smtp.gmail.com', 'type' => 'text', 'group' => 'mail', 'order_column' => 10],
            ['key' => 'mail_port', 'display_name' => 'SMTP Port', 'value' => '587', 'type' => 'text', 'group' => 'mail', 'order_column' => 11],
            ['key' => 'mail_encryption', 'display_name' => 'Enkripsi (tls/ssl)', 'value' => 'tls', 'type' => 'text', 'group' => 'mail', 'order_column' => 12],
            ['key' => 'mail_username', 'display_name' => 'Username Email', 'value' => '', 'type' => 'text', 'group' => 'mail', 'order_column' => 13],
            ['key' => 'mail_password', 'display_name' => 'Password / App Password', 'value' => '', 'type' => 'password', 'group' => 'mail', 'order_column' => 14],
            ['key' => 'mail_from_address', 'display_name' => 'Alamat Pengirim (From Email)', 'value' => 'noreply@simkendaraan.go.id', 'type' => 'text', 'group' => 'mail', 'order_column' => 15],
            ['key' => 'mail_from_name', 'display_name' => 'Nama Pengirim (From Name)', 'value' => 'SIM Kendaraan', 'type' => 'text', 'group' => 'mail', 'order_column' => 16],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

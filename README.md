<div align="center">

<img src="https://ozanproject.site/logo.png" alt="Ozan Project" width="80" style="border-radius:16px"/>

# 🚗 SIM Pemeliharaan Kendaraan Dinas

### Sistem Informasi Manajemen Pemeliharaan Kendaraan Dinas Operasional

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-Volt-4E56A6?style=for-the-badge&logo=livewire&logoColor=white)](https://livewire.laravel.com)
[![TailwindCSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)

> Pantau, catat, dan kelola seluruh armada kendaraan dinas operasional dengan lebih efisien dan terstruktur dalam satu platform terpusat.

---

<a href="https://ozanproject.site" target="_blank"><img src="https://img.shields.io/badge/🌐 Website-ozanproject.site-1145d4?style=for-the-badge" alt="Website Ozan Project"/></a>
&nbsp;
<a href="https://youtube.com/ozanproject" target="_blank"><img src="https://img.shields.io/badge/▶ YouTube-Ozan Project-FF0000?style=for-the-badge&logo=youtube" alt="YouTube Ozan Project"/></a>

</div>

---

## 📋 Daftar Isi

- [Tentang Sistem](#-tentang-sistem)
- [Fitur Utama](#-fitur-utama)
- [Alur Sistem](#-alur-sistem)
- [Stack Teknologi](#-stack-teknologi)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Panduan Instalasi](#-panduan-instalasi)
- [Struktur Direktori](#-struktur-direktori)
- [Role & Hak Akses](#-role--hak-akses)
- [Modul Sistem](#-modul-sistem)
- [Developer](#-developer)

---

## 🎯 Tentang Sistem

**SIM Pemeliharaan Kendaraan Dinas** adalah aplikasi web berbasis Laravel yang dirancang untuk membantu instansi pemerintah atau perusahaan dalam mengelola dan memantau:

- 📋 **Registrasi Armada** — Pendataan unit kendaraan dinas beserta pagu anggaran per tahun
- 🧾 **Pencatatan Belanja** — Realisasi pengeluaran BBM dan pemeliharaan servis kendaraan
- 📊 **Laporan Realisasi** — Rekap dan ekspor laporan anggaran ke format **PDF** dan **Excel**
- 🌐 **Halaman Publik** — Landing page instansi yang dapat dikonfigurasi tanpa coding
- 🔔 **Notifikasi Real-time** — Pemberitahuan otomatis untuk setiap transaksi baru

---

## ✨ Fitur Utama

### 🖥️ Dashboard & Analitik
- Ringkasan unit kendaraan aktif
- Grafik realisasi anggaran vs pagu
- Widget data terkini pencatatan belanja
- Navigasi cepat ke semua modul

### 🚗 Manajemen Unit Kendaraan
- CRUD data kendaraan dinas (nama, plat nomor, jenis)
- Penetapan pagu anggaran tahunan per unit
- Riwayat lengkap pengeluaran per unit

### 💰 Pencatatan Realisasi Anggaran
- Input pengeluaran dengan tanggal, kategori, nominal, dan deskripsi
- Filter berdasarkan unit kendaraan dan periode waktu
- Validasi pagu anggaran otomatis

### 📂 Laporan & Ekspor
- **Filter Dinamis**: unit, periode (bulan/tahun), atau rentang kustom
- **Export PDF**: Header instansi dinamis + rekap tabel + total + footer
- **Export Excel (.xlsx)**: Header berformat multi-baris + baris TOTAL otomatis
- **Judul laporan dinamis**: dikonfigurasi dari Pengaturan Admin

### 🔔 Sistem Notifikasi
- Notifikasi database setiap kali belanja baru dicatat
- Indikator jumlah notifikasi belum dibaca di Navbar
- Dropdown daftar notifikasi dengan detail transaksi
- Tombol "Tandai semua sudah dibaca" (Mark All as Read)

### 👤 Manajemen Pengguna
- Multi-role: **Super Admin** dan **Pengguna Biasa**
- CRUD pengguna dengan validasi keunikan email
- Proteksi akun Super Admin dari penghapusan tidak sengaja
- Profil avatar dengan Upload Foto

### ⚙️ Pengaturan Sistem (CMS Admin)
| Grup | Konfigurasi |
|------|-------------|
| **Umum** | Nama Aplikasi, Logo, Judul Laporan Cetak |
| **Instansi** | Nama, Alamat, Kontak Instansi |
| **Halaman Depan** | Hero Banner (Upload), Judul, Sub-judul |
| **Fitur** | Judul & Deskripsi 3 Item Fitur Utama |
| **Tentang** | Judul & Konten Halaman About Us |
| **Sosial Media** | Link Website, Instagram |

### 🌐 Landing Page (Halaman Publik)
- Hero section dengan gambar banner (upload/URL)
- Seksi Fitur Utama (3 kolom, dinamis)
- Cara kerja sistem (3 langkah)
- Call-to-Action terarah ke Login
- Header navigasi **Sticky** dengan efek Glassmorphism
- **Hamburger Menu** responsif untuk mobile
- Footer dengan kontak & sosial media

### 🎨 UI/UX Modern
- Dark Mode / Light Mode otomatis
- SweetAlert2 untuk semua notifikasi aksi (Sukses, Error, Konfirmasi)
- Animasi transisi halus (Alpine.js)
- Antarmuka responsif (Mobile-first Design)

---

## 🔄 Alur Sistem

```
Pengunjung/Publik
    │
    ▼
┌─────────────────┐
│  Landing Page   │  ← Beranda, Layanan, Tentang Kami (CMS Admin)
└────────┬────────┘
         │ Klik Login
         ▼
┌─────────────────┐
│   Halaman Login │  ← Autentikasi Laravel Breeze
└────────┬────────┘
         │
     ┌───┴───┐
     ▼       ▼
[Super Admin]  [Pengguna Biasa]
     │              │
     │         ┌────┴──────────┐
     │         │ Dashboard     │ ← Ringkasan & Grafik
     │         │ Unit          │ ← Lihat & Input Kendaraan
     │         │ Anggaran      │ ← Lihat Pagu Per Unit
     │         │ Realisasi     │ ← Catat & Lihat Pengeluaran
     │         │ Laporan       │ ← Filter & Export PDF/Excel
     │         │ Profil        │ ← Edit Nama, Password, Avatar
     │         └───────────────┘
     │
     └──── [TAMBAHAN SUPER ADMIN]
           │ Manajemen Pengguna
           │ Pengaturan Sistem (Nama App, Logo, dll)
           └ Pengaturan Halaman Depan (Banner, Fitur, dll)
```

### Alur Pencatatan Belanja
```
Admin Input Pengeluaran
    │
    ▼
Validasi Data (Tanggal, Unit, Nominal, Deskripsi)
    │
    ▼
Simpan ke Tabel expenses
    │
    ├──► Kirim Notifikasi Database (NewExpenseNotification)
    │       └──► Tampil di Navbar semua user
    │
    └──► Konfirmasi SweetAlert "Berhasil Disimpan"
```

### Alur Ekspor Laporan
```
Pengguna Pilih Filter (Unit/Periode)
    │
    └──► Klik Export PDF / Export Excel
              │
              ▼
    ReportController::export()
              │
         ┌────┴──────┐
         ▼           ▼
     [PDF]       [Excel]
   DomPDF       Maatwebsite
   .blade.php   ExpensesExport.php
         │           │
         ▼           ▼
   Judul Dinamis dari Settings (app_name + report_title)
   Tabel Data Pengeluaran
   Total Nominal (SUM otomatis)
   Footer + Tanggal Cetak
```

---

## 🛠 Stack Teknologi

| Kategori | Teknologi |
|----------|-----------|
| **Backend Framework** | Laravel 12.x |
| **Frontend Interaktif** | Livewire 3 + Volt |
| **Animasi & UI** | Alpine.js |
| **Styling** | Tailwind CSS v3 |
| **Database ORM** | Eloquent (Laravel) |
| **Autentikasi** | Laravel Breeze |
| **Export Excel** | Maatwebsite/Laravel-Excel |
| **Export PDF** | Barryvdh/Laravel-DomPDF |
| **Notifikasi Popup** | SweetAlert2 |
| **Ikon** | Material Symbols (Google) |
| **Font** | Inter (Google Fonts) |
| **Build Tool** | Vite |
| **PHP Version** | 8.3+ |

---

## 📦 Persyaratan Sistem

Sebelum instalasi, pastikan sistem Bapak/Ibu memiliki:

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x & **NPM** >= 9.x
- **MySQL** >= 8.0 / MariaDB >= 10.4
- **Web Server**: Apache / Nginx / Laragon (direkomendasikan)
- **Extension PHP**: `pdo`, `mbstring`, `zip`, `gd`, `fileinfo`, `xml`

---

## 🚀 Panduan Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/username/sim-kendaraan.git
cd sim-kendaraan
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Install Dependensi Node.js

```bash
npm install
```

### 4. Konfigurasi Environment

```bash
# Salin file environment
cp .env.example .env

# Generate Application Key
php artisan key:generate
```

Kemudian edit file `.env` dan sesuaikan:

```env
APP_NAME="SIM Pemeliharaan Kendaraan"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sim_kendaraan
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrasi Database & Seeder

```bash
# Jalankan migrasi tabel
php artisan migrate

# Isi data awal (settings, pengguna admin, dll)
php artisan db:seed

# Atau sekaligus (fresh)
php artisan migrate:fresh --seed
```

### 6. Link Storage (untuk upload gambar)

```bash
php artisan storage:link
```

### 7. Build Asset Frontend

```bash
# Mode Development
npm run dev

# Mode Production (build)
npm run build
```

### 8. Jalankan Server

```bash
php artisan serve
```

Buka browser: **http://127.0.0.1:8000**

---

### 🔑 Akun Default Setelah Seeder

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@example.com | password |

> ⚠️ Segera ganti password setelah login pertama kali!

---

## 📁 Struktur Direktori

```
sim-kendaraan/
├── app/
│   ├── Exports/
│   │   └── ExpensesExport.php          # Kelas ekspor Excel (Maatwebsite)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── ReportController.php    # Kontroller ekspor PDF & Excel
│   │   └── Middleware/
│   │       └── IsAdmin.php             # Middleware role Super Admin
│   ├── Livewire/
│   │   └── Actions/
│   │       └── Logout.php
│   ├── Models/
│   │   ├── User.php                    # Model pengguna + avatar
│   │   ├── Unit.php                    # Model kendaraan dinas
│   │   ├── Budget.php                  # Model pagu anggaran
│   │   ├── Expense.php                 # Model realisasi belanja
│   │   ├── Setting.php                 # Model pengaturan sistem
│   │   └── FrontendSetting.php         # Model pengaturan landing page
│   ├── Notifications/
│   │   └── NewExpenseNotification.php  # Format notifikasi belanja baru
│   └── Providers/
│       └── AppServiceProvider.php      # Share data global (settings)
│
├── database/
│   ├── migrations/                     # Semua file migrasi tabel
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── SettingSeeder.php           # Data awal pengaturan sistem
│       └── FrontendSettingSeeder.php   # Data awal landing page
│
├── resources/
│   ├── views/
│   │   ├── backend/
│   │   │   ├── layouts/app.blade.php   # Layout utama dashboard
│   │   │   └── components/
│   │   │       ├── navbar.blade.php    # Navigasi atas + notifikasi
│   │   │       └── sidebar.blade.php   # Menu samping
│   │   ├── frontend/
│   │   │   ├── layouts/app.blade.php   # Layout halaman publik
│   │   │   ├── components/
│   │   │   │   ├── header.blade.php    # Navbar publik (sticky+hamburger)
│   │   │   │   └── footer.blade.php    # Footer dengan sosial media
│   │   │   └── pages/
│   │   │       ├── home.blade.php      # Halaman Beranda
│   │   │       ├── services.blade.php  # Halaman Layanan
│   │   │       └── about.blade.php     # Halaman Tentang Kami
│   │   ├── livewire/
│   │   │   ├── dashboard.blade.php     # Dashboard (Volt)
│   │   │   ├── units/index.blade.php   # Modul Kendaraan (Volt)
│   │   │   ├── budgets/index.blade.php # Modul Anggaran (Volt)
│   │   │   ├── expenses/index.blade.php # Modul Realisasi (Volt)
│   │   │   ├── reports/index.blade.php # Modul Laporan (Volt)
│   │   │   ├── users/index.blade.php   # Modul Pengguna (Volt)
│   │   │   ├── settings/
│   │   │   │   ├── index.blade.php     # Pengaturan Sistem (Volt)
│   │   │   │   └── frontend.blade.php  # Pengaturan Landing Page (Volt)
│   │   │   └── profile/
│   │   │       ├── update-profile-information-form.blade.php
│   │   │       ├── update-password-form.blade.php
│   │   │       └── delete-user-form.blade.php
│   │   └── reports/
│   │       └── pdf.blade.php           # Template cetak PDF
│   └── profile.blade.php               # Halaman profil pengguna
│
├── routes/
│   └── web.php                         # Semua definisi route
│
└── README.md
```

---

## 👥 Role & Hak Akses

| Fitur | Pengguna Biasa | Super Admin |
|-------|:--------------:|:-----------:|
| Dashboard | ✅ | ✅ |
| Lihat Unit Kendaraan | ✅ | ✅ |
| Kelola Unit Kendaraan | ✅ | ✅ |
| Lihat & Input Anggaran | ✅ | ✅ |
| Lihat & Input Realisasi | ✅ | ✅ |
| Laporan & Export PDF/Excel | ✅ | ✅ |
| Notifikasi Real-time | ✅ | ✅ |
| Edit Profil & Avatar | ✅ | ✅ |
| **Manajemen Pengguna** | ❌ | ✅ |
| **Pengaturan Sistem** | ❌ | ✅ |
| **Pengaturan Landing Page** | ❌ | ✅ |

---

## 📌 Modul Sistem

### Dashboard
Halaman utama setelah login menampilkan:
- Total unit kendaraan aktif
- Total pagu anggaran keseluruhan
- Total realisasi belanja
- Grafik perbandingan anggaran vs realisasi
- Tabel transaksi terbaru

### Manajemen Unit Kendaraan (`/units`)
- Daftar kendaraan dinas dalam tabel paginasi
- Tambah/Edit: Nama Unit, Plat Nomor, Jenis Kendaraan
- Hapus dengan konfirmasi SweetAlert
- Pencarian dan filter

### Manajemen Anggaran (`/budgets`)
- Penetapan pagu anggaran per unit per tahun
- Pantau sisa saldo anggaran otomatis
- Tambah/Edit/Hapus dengan validasi

### Realisasi Anggaran (`/expenses`)
- Input pengeluaran harian (BBM, servis, dll)
- Field: Tanggal, Unit, Deskripsi, Nominal (Rp)
- Trigger notifikasi otomatis ke seluruh pengguna
- Riwayat pencatatan lengkap dengan filter

### Laporan (`/reports`)
- Filter: Unit Kendaraan, Bulan, Tahun
- Preview data sebelum ekspor
- **Ekspor PDF**: Dokumen resmi berformat A4 Portrait
- **Ekspor Excel**: Spreadsheet berformat dengan total otomatis

### Pengaturan Sistem (`/settings`)
Admin dapat mengonfigurasi:
- `app_name` — Nama aplikasi (tampil di Header & Footer)
- `app_logo` — Logo (upload file)
- `report_title` — Judul cetak laporan utama
- `agency_name` — Nama instansi resmi
- `agency_address` — Alamat instansi
- `agency_contact` — Nomor telepon/email

### Pengaturan Landing Page (`/settings/frontend`)
- Tab **Beranda & Hero**: Judul, Sub-judul, Gambar Banner (Upload)
- Tab **Layanan Utama**: Heading, Deskripsi, 3 item fitur
- Tab **Informasi & Sosial Media**: Tentang Us, Kontak, Link Sosmed

---

## 👨‍💻 Developer

<div align="center">

### Dibuat dengan ❤️ oleh

<a href="https://ozanproject.site" target="_blank">
  <img src="https://img.shields.io/badge/🧑‍💻_Ozan_Project-Developer_&_Educator-1145d4?style=for-the-badge&labelColor=0a0a0a" alt="Ozan Project Developer"/>
</a>

<br/><br/>

| Platform | Link |
|----------|------|
| 🌐 **Website** | [ozanproject.site](https://ozanproject.site) |
| ▶️ **YouTube** | [youtube.com/ozanproject](https://youtube.com/ozanproject) |

<br/>

> *"Membangun solusi digital yang berdampak nyata untuk kemajuan tata kelola instansi Indonesia."*

</div>

---

## 📜 Lisensi

Proyek ini dikembangkan oleh **Ozan Project** untuk keperluan internal instansi.
Dilarang mendistribusikan ulang tanpa seizin developer.

© 2026 [Ozan Project](https://ozanproject.site) — All Rights Reserved.

---

<div align="center">

⭐ **Jika proyek ini membantu, jangan lupa berikan bintang dan dukung konten kami!** ⭐

<a href="https://ozanproject.site" target="_blank">Website</a> · <a href="https://youtube.com/ozanproject" target="_blank">YouTube</a>

</div>

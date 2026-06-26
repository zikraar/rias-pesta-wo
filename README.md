# Rias Pesta Wedding Organizer

Aplikasi web manajemen Wedding Organizer (studi kasus **Rias Pesta Pekanbaru**) berbasis **Laravel 12**. Aplikasi ini digunakan untuk mengelola pemesanan paket pernikahan, pembayaran, progres pengerjaan acara, portofolio galeri, dan laporan — dengan tiga peran pengguna: **Super Admin**, **Admin**, dan **Customer**.

## Daftar Isi

- [Tech Stack](#tech-stack)
- [Fitur Utama](#fitur-utama)
- [Struktur Peran Pengguna](#struktur-peran-pengguna)
- [Struktur Proyek](#struktur-proyek)
- [Skema Database](#skema-database)
- [Instalasi & Setup](#instalasi--setup)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Akun Default (Seeder)](#akun-default-seeder)
- [Alur Kerja (Workflow)](#alur-kerja-workflow)
- [Testing](#testing)

## Tech Stack

**Backend**
- Laravel 12 (PHP ^8.2)
- MySQL (driver default di `.env`, SQLite tersedia untuk dev cepat via `.env.example`)
- Laravel Breeze — scaffolding autentikasi
- `spatie/laravel-permission` — tabel roles & permissions (role utama saat ini masih disimpan di kolom `users.role`)
- `spatie/laravel-activitylog` — pencatatan aktivitas
- `barryvdh/laravel-dompdf` — generate PDF (invoice booking & laporan)
- `intervention/image-laravel` — pengolahan gambar (upload paket, portofolio, bukti transfer)

**Frontend**
- Blade templates
- Tailwind CSS + `@tailwindcss/forms`
- Alpine.js
- Vite sebagai build tool
- Axios

**Lainnya**
- Queue & cache berbasis database
- Notifikasi disimpan di tabel `notifications` (database channel)
- Mail via SMTP (Mailtrap untuk development)

## Fitur Utama

### Halaman Publik
- Beranda dengan paket unggulan & portofolio pilihan
- Daftar paket layanan (`/paket`)
- Galeri portofolio dengan filter kategori (`/portfolio`)
- Halaman kontak (`/kontak`)

### Customer
- Registrasi & login
- Dashboard ringkasan booking (pending, in progress, completed)
- Membuat booking acara (cek konflik tanggal otomatis)
- Melihat detail booking, progres pengerjaan, dan riwayat acara
- Upload bukti pembayaran (transfer) dan melihat riwayat pembayaran
- Kelola profil

### Admin / Super Admin
- Dashboard statistik (jumlah booking per status, jumlah customer, revenue bulanan, grafik 6 bulan terakhir, acara mendatang)
- Manajemen booking (lihat detail, ubah status, generate invoice PDF)
- Verifikasi/penolakan pembayaran beserta catatan admin
- Manajemen progres pengerjaan per booking (milestone, target tanggal, lampiran)
- Manajemen paket layanan (CRUD, upload gambar)
- Manajemen kalender acara
- Manajemen galeri portofolio (CRUD, tandai unggulan)
- Manajemen pengguna (admin & customer)
- Laporan berdasarkan rentang tanggal + export PDF

### Notifikasi Otomatis
Tersimpan di database dan tampil ke customer saat:
- Status booking berubah (`BookingStatusUpdated`)
- Pembayaran diverifikasi (`PaymentVerified`)
- Pembayaran ditolak (`PaymentRejected`)
- Progres pengerjaan diperbarui (`ProgressUpdated`)

## Struktur Peran Pengguna

Role disimpan pada kolom `users.role` (enum: `superadmin`, `admin`, `customer`), dengan helper di `App\Models\User`:

```php
$user->isSuperAdmin(); // role === 'superadmin'
$user->isAdmin();      // role in ['admin', 'superadmin']
$user->isCustomer();   // role === 'customer'
```

Akses route dibatasi lewat middleware kustom `App\Http\Middleware\CheckRole`, dipasang sebagai `role:admin,superadmin` atau `role:customer` pada grup route terkait.

Setelah login, route `GET /dashboard` mengarahkan pengguna sesuai role:
- `superadmin` / `admin` → `/admin/dashboard`
- `customer` → `/customer/dashboard`

## Struktur Proyek

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/         # DashboardController, BookingController, PaymentController,
│   │   │                  # ProgressController, PackageController, EventController,
│   │   │                  # PortfolioController, UserController, ReportController
│   │   ├── Customer/       # DashboardController, BookingController,
│   │   │                  # PaymentController, ProgressController
│   │   ├── Auth/           # Hasil scaffolding Laravel Breeze
│   │   ├── HomeController.php
│   │   └── ProfileController.php
│   ├── Middleware/CheckRole.php
│   └── Requests/
├── Models/
│   ├── User.php
│   ├── Booking.php
│   ├── BookingPackage.php   # tabel pivot booking <-> package (price snapshot)
│   ├── Package.php
│   ├── Payment.php
│   ├── Progress.php
│   ├── Event.php
│   └── Portfolio.php
└── Notifications/
    ├── BookingStatusUpdated.php
    ├── PaymentVerified.php
    ├── PaymentRejected.php
    └── ProgressUpdated.php

resources/views/
├── home/            # beranda, paket, portfolio, kontak (publik)
├── auth/            # login, register, reset password, dst.
├── customer/        # dashboard, bookings, payments, profile
├── admin/           # dashboard, bookings, packages, payments, progress,
│                    # events, portfolios, users, reports
├── pdf/             # invoice.blade.php, report.blade.php
├── layouts/ & components/

database/
├── migrations/      # users, packages, portfolios, bookings, booking_packages,
│                    # payments, progress, events, notifications, permission tables
└── seeders/         # DatabaseSeeder, UserSeeder, PackageSeeder, PortfolioSeeder

routes/
├── web.php          # route publik, customer (/customer), admin (/admin)
└── auth.php         # route bawaan Laravel Breeze
```

## Skema Database

Entitas inti dan relasinya:

- **users** — akun login dengan `role`, `phone`, `address`, `avatar`, `is_active`
- **packages** — paket layanan (`name`, `category`, `price`, `description`, `max_guests`, `image`, `is_active`)
- **bookings** — pemesanan acara (`booking_code` unik format `WO-2026-XXXX`, relasi ke `user`, tanggal & lokasi acara, nama pengantin, `event_type` [`akad`/`resepsi`/`akad_resepsi`], `status` [`pending`/`confirmed`/`in_progress`/`completed`/`cancelled`], `total_price`)
- **booking_packages** — pivot booking ↔ package, menyimpan `price_snapshot` saat booking dibuat
- **payments** — transaksi pembayaran (`payment_code`, `payment_type` [`dp`/`pelunasan`/`full`], bukti transfer, `status` [`pending`/`verified`/`rejected`], `verified_by`, `verified_at`)
- **progress** — milestone pengerjaan per booking (`title`, `status` [`pending`/`on_progress`/`done`], `order`, `target_date`, `completed_date`, lampiran)
- **events** — entri kalender terkait booking (tanggal, lokasi, tipe, warna)
- **notifications** — notifikasi database bawaan Laravel
- Tabel **roles/permissions** dari `spatie/laravel-permission` sudah dimigrasikan, namun pengecekan role aktif saat ini masih memakai kolom `users.role` + middleware `CheckRole`

## Instalasi & Setup

### Prasyarat
- PHP ^8.2 dengan ekstensi standar Laravel
- Composer
- Node.js & npm
- MySQL (atau gunakan SQLite untuk pengembangan cepat)

### Langkah Setup

```bash
# 1. Clone / masuk ke folder proyek
cd wedding-organizer

# 2. Install dependency PHP
composer install

# 3. Install dependency JS
npm install

# 4. Siapkan file environment
cp .env.example .env
php artisan key:generate

# 5. Sesuaikan koneksi database di .env
# (default project ini memakai MySQL: DB_DATABASE=wedding_organizer)

# 6. Jalankan migrasi + seeder
php artisan migrate --seed

# 7. Build asset frontend
npm run build
```

> Project ini juga menyediakan shortcut `composer setup` yang menjalankan composer install, copy `.env`, `key:generate`, `migrate --force`, `npm install`, dan `npm run build` secara berurutan.

## Menjalankan Aplikasi

Mode development (server, queue listener, log viewer, dan Vite berjalan sekaligus):

```bash
composer dev
```

Atau jalankan manual per proses:

```bash
php artisan serve              # server Laravel
php artisan queue:listen       # worker queue (database driver)
npm run dev                    # Vite dev server (hot reload)
```

Aplikasi dapat diakses di `http://localhost:8000`.

## Akun Default (Seeder)

`DatabaseSeeder` membuat akun berikut (jalankan `php artisan db:seed` jika belum):

| Role | Email | Password |
|---|---|---|
| Admin | `admin@riaspesta.com` | `admin123` |
| Customer | `customer@demo.com` | `password` |

Seeder juga membuat 6 paket layanan nyata milik Rias Pesta Pekanbaru: **Silver** (Rp8.000.000), **Gold** (Rp14.000.000), **Platinum I** (Rp16.500.000), **Platinum II** (Rp18.000.000), **Diamond I** (Rp20.000.000), **Diamond II** (Rp22.000.000), masing-masing dengan rincian dekorasi, tenda, dan attire/makeup.

> Ada pula `UserSeeder` terpisah yang menyiapkan akun `superadmin@riaspesta.com` jika dibutuhkan skenario tiga role sekaligus.

## Alur Kerja (Workflow)

1. **Customer mendaftar/login**, lalu membuat booking melalui `/bookings` — memilih paket, tanggal, lokasi, dan detail acara. Sistem mengecek konflik tanggal sebelum booking disimpan.
2. **Booking masuk dengan status `pending`** dan tampil di dashboard admin.
3. **Customer mengunggah bukti pembayaran** (DP/pelunasan/full) lewat `/payments/create`. Status pembayaran awal `pending`.
4. **Admin memverifikasi atau menolak pembayaran** dari `/admin/payments/{payment}`. Jika diverifikasi, customer menerima notifikasi `PaymentVerified`; jika ditolak, menerima `PaymentRejected` beserta catatan admin.
5. **Admin mengelola progres pengerjaan** acara (milestone dekorasi, persiapan, dsb.) lewat `/admin/progress`, yang bisa dipantau customer di halaman detail booking-nya. Setiap update memicu notifikasi `ProgressUpdated`.
6. **Admin memperbarui status booking** (`confirmed` → `in_progress` → `completed`, atau `cancelled`), memicu notifikasi `BookingStatusUpdated` ke customer.
7. **Admin dapat menerbitkan invoice PDF** booking dan **laporan PDF** berdasarkan rentang tanggal dari menu `/admin/reports`.
8. **Kalender acara** (`/admin/events`) dan **galeri portofolio** (`/admin/portfolios`, tampil publik di `/portfolio`) dikelola terpisah oleh admin untuk kebutuhan operasional dan promosi.

## Testing

```bash
composer test
# atau
php artisan test
```

Konfigurasi pengujian menggunakan `phpunit.xml` di root proyek.

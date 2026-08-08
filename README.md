# Kasir Sederhana

Aplikasi kasir (POS) sederhana berbasis **Laravel 11 murni** dengan tampilan **monochrome** yang bersih dan ringan. Dibuat tanpa framework front-end/build step — cukup PHP, MySQL, dan browser.

---

## ✨ Fitur

- **Autentikasi & Role** — login dengan dua peran: **admin** dan **kasir**.
- **Dashboard** — omzet & jumlah transaksi hari ini, produk aktif, transaksi terakhir, stok menipis.
- **Produk** (admin) — CRUD lewat modal, pencarian, **sort** kolom (SKU/Nama/Harga/Stok), pagination, dan **soft delete** (riwayat penjualan tetap aman).
- **Penjualan / POS** — keranjang real-time, **diskon** per transaksi, **metode pembayaran** (Tunai/QRIS/Transfer), hitung kembalian, cetak struk.
- **Batal transaksi** — void mengembalikan stok & dikecualikan dari omzet (otorisasi admin / kasir pemilik).
- **Laporan** — filter periode, ringkasan omzet, **produk terlaris**, **rekap per kasir**, dan **export CSV**.
- **User** (admin) — kelola akun admin & kasir.
- **Akun** — setiap user bisa mengganti passwordnya sendiri.
- **Pengaturan Toko** (admin) — nama, alamat, telepon, catatan kaki struk (tampil di struk).
- **Format ribuan** otomatis pada semua input & tampilan nominal (harga, diskon, bayar, stok).

## 🧰 Tech Stack

- **Laravel 11** (PHP 8.2+)
- **Blade** + **CSS statis** monochrome (tanpa Vite/Tailwind/Node)
- **MySQL / MariaDB**
- Sedikit **vanilla JavaScript** untuk keranjang POS & format input

---

## 📋 Prasyarat

Pastikan sudah terpasang di laptop:

- **PHP 8.2+** & **Composer**
- **MySQL / MariaDB** (paling mudah lewat **XAMPP**)
- **Git**

Cek versi:

```bash
php -v
composer -V
git --version
```

---

## 🚀 Cara Setup Setelah Clone

### 1. Clone repository

```bash
git clone https://github.com/InMyDr3am/laravel-kasir-sederhana.git
cd laravel-kasir-sederhana
```

### 2. Install dependency PHP

```bash
composer install
```

### 3. Siapkan file `.env`

```bash
cp .env.example .env
```

> Windows PowerShell: `Copy-Item .env.example .env`

Template `.env.example` sudah dikonfigurasi untuk MySQL dengan database `kasir_sederhana`. Jika MySQL kamu memakai password root, sesuaikan baris `DB_PASSWORD=` di `.env`.

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Nyalakan MySQL & buat database

Nyalakan MySQL (di XAMPP: **Start** pada baris MySQL), lalu buat database:

```bash
mysql -u root -e "CREATE DATABASE kasir_sederhana CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

> Jika perintah `mysql` tidak dikenali, gunakan path XAMPP:
> `C:\xampp\mysql\bin\mysql -u root -e "..."` — atau buat database `kasir_sederhana` lewat **phpMyAdmin** (`http://localhost/phpmyadmin`).

### 6. Migrasi database + load data awal (seed)

```bash
php artisan migrate --seed
```

Perintah ini membuat seluruh tabel **dan** mengisi data awal: 2 akun, 30 produk contoh, dan pengaturan toko default.

### 7. Jalankan aplikasi

```bash
php artisan serve
```

Buka di browser: **http://127.0.0.1:8000**

---

## 👤 Akun Demo

| Role  | Email               | Password   |
| ----- | ------------------- | ---------- |
| Admin | `admin@kasir.test`  | `password` |
| Kasir | `kasir@kasir.test`  | `password` |

- **Admin**: akses penuh (Produk, User, Pengaturan) + semua fitur kasir.
- **Kasir**: Penjualan (POS), Laporan, dan Akun (ganti password sendiri).

> ⚠️ Password default hanya untuk demo. Ganti lewat menu **Akun** setelah login, atau ubah daftar akun di `database/seeders/DatabaseSeeder.php`.

---

## ⚡ Ringkas (copy-paste berurutan)

```bash
git clone https://github.com/InMyDr3am/laravel-kasir-sederhana.git
cd laravel-kasir-sederhana
composer install
cp .env.example .env
php artisan key:generate
mysql -u root -e "CREATE DATABASE kasir_sederhana CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate --seed
php artisan serve
```

---

## 🔧 Catatan & Troubleshooting

- **Tidak perlu `npm install`** — tampilan memakai CSS statis (`public/css/app.css`), tanpa build step.
- **Reset data ke kondisi awal** (hapus semua transaksi & kembalikan seed):
  ```bash
  php artisan migrate:fresh --seed
  ```
- **Error `Access denied for user 'root'`** → sesuaikan `DB_USERNAME` / `DB_PASSWORD` di `.env` dengan MySQL kamu.
- **Error `Unknown database 'kasir_sederhana'`** → langkah 5 belum dijalankan atau MySQL belum menyala.
- **Setelah mengubah `.env`** → jalankan `php artisan config:clear`.
- **Port 8000 dipakai** → jalankan di port lain: `php artisan serve --port=8080`.

---

## 📁 Struktur Singkat

```
app/
  Http/Controllers/   # Dashboard, Product, Sale, Report, User, Account, StoreSetting, Auth
  Http/Requests/      # Validasi form (Product, User, Checkout)
  Models/             # User, Product, Sale, SaleItem, Setting
  Services/           # SaleService — logika transaksi & void (DB transaction)
database/
  migrations/         # Skema tabel
  seeders/            # Data awal (akun, produk, pengaturan)
resources/views/      # Blade: layout, auth, dashboard, products, sales, reports, users, account, settings
public/css/app.css    # Styling monochrome
routes/web.php        # Definisi rute
```

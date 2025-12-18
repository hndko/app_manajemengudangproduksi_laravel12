# 🏭 Aplikasi Manajemen Gudang Produksi

<p align="center">
  <img src="public/images/logo.webp" alt="Mari Partner Logo" width="150">
</p>

<p align="center">
  <strong>Sistem Manajemen Gudang Produksi Lengkap dengan Akuntansi Manufaktur</strong>
</p>

<p align="center">
  <a href="#fitur">Fitur</a> •
  <a href="#teknologi">Teknologi</a> •
  <a href="#instalasi">Instalasi</a> •
  <a href="#penggunaan">Penggunaan</a> •
  <a href="#lisensi">Lisensi</a>
</p>

---

## 📋 Tentang Aplikasi

Aplikasi Manajemen Gudang Produksi adalah solusi lengkap untuk mengelola operasional gudang, proses manufaktur, dan pembukuan keuangan dalam satu platform terintegrasi. Didesain untuk efisiensi maksimal dengan antarmuka yang modern dan responsif.

## ✨ Fitur

### 🖥️ Modul Interface
- ✅ Dashboard Grafik untuk Analisis Cepat
- ✅ Dark Mode untuk Kenyamanan
- ✅ Halaman Responsif yang Mudah Dijelajahi
- ✅ Login Admin/User yang Aman

### 👥 Modul Kepegawaian
- ✅ 3 User Utama (Admin Akuntansi, Warehouse, Ekspedisi)
- ✅ Absensi untuk Efisiensi
- ✅ Log Aktivitas User untuk Kendali Penuh

### 💰 Modul Akuntansi
- ✅ Chart of Accounts untuk Manajemen Keuangan yang Tepat
- ✅ Jurnal, Buku Besar, dan Neraca
- ✅ Laporan Keuangan (Laba Rugi dan Posisi Keuangan)
- ✅ Cetak Laporan dengan Mudah

### 📁 Modul Data
- ✅ Master Data (Konsumen, Kategori, Satuan, Jenis Harga, Warehouse, Jenis Cicilan)
- ✅ Rekap Data (Stok, Tim Produksi, Surat Jalan, Nota Transaksi)
- ✅ Unduh Rekap dengan Sekali Klik
- ✅ Backup Data untuk Keamanan

### 🏪 Modul Warehouse
- ✅ Kelola Material dengan Mudah
- ✅ Rekam Stok Bahan
- ✅ Siapkan Produk untuk Dijual

### ⚙️ Modul Manufaktur
- ✅ Tambah Stok Bahan dengan Cepat
- ✅ Produksi Barang dengan Efisiensi
- ✅ Kelola Surat Jalan dan Retur Barang Ekspedisi

### 💳 Modul Transaksi
- ✅ Kelola Transaksi Penjualan
- ✅ Catat Pengeluaran dengan Tepat

### 🧮 Modul Perhitungan
- ✅ Diagram dan Statistik yang Memudahkan Analisis
- ✅ Kalkulator PPh21 untuk Kepatuhan Pajak

### ⚙️ Modul Setting
- ✅ Detail Web yang Dapat Dikonfigurasi
- ✅ Kelola Profil dan User Karyawan dengan Mudah

## 🛠️ Teknologi

| Teknologi | Versi |
|-----------|-------|
| PHP | 8.2+ |
| Laravel | 12.x |
| MySQL | 8.0+ |
| TailwindCSS | 4.x |
| Vite | 6.x |
| Chart.js | 4.x |
| Alpine.js | 3.x |

## 📦 Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL >= 8.0

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/mari-partner/gudang-produksi.git
   cd gudang-produksi
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database**

   Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gudang_produksi
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Migrasi Database**
   ```bash
   php artisan migrate --seed
   ```

6. **Compile Assets**
   ```bash
   npm run build
   ```

7. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```

   Akses aplikasi di: `http://localhost:8000`

## 👤 Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin Akuntansi | admin@example.com | password |
| Warehouse | warehouse@example.com | password |
| Ekspedisi | ekspedisi@example.com | password |

## 📸 Screenshots

<details>
<summary>Dashboard</summary>

![Dashboard](docs/screenshots/dashboard.png)

</details>

<details>
<summary>Jurnal Akuntansi</summary>

![Jurnal](docs/screenshots/jurnal.png)

</details>

<details>
<summary>Manajemen Stok</summary>

![Stok](docs/screenshots/stok.png)

</details>

## 📂 Struktur Proyek

```
app_manajemengudangproduksi_laravel12/
├── app/
│   ├── Http/Controllers/      # Controllers per module
│   ├── Models/                # Eloquent models
│   └── Services/              # Business logic
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Data seeders
├── resources/
│   ├── css/                   # TailwindCSS styles
│   ├── js/                    # JavaScript files
│   └── views/                 # Blade templates
├── routes/
│   └── web.php                # Web routes
└── public/
    └── images/                # Public images
```

## 🔐 Role & Permissions

| Fitur | Admin Akuntansi | Warehouse | Ekspedisi |
|-------|:---------------:|:---------:|:---------:|
| Dashboard | ✅ | ✅ | ✅ |
| Akuntansi | ✅ | ❌ | ❌ |
| Master Data | ✅ | ✅ | ❌ |
| Warehouse | ✅ | ✅ | ❌ |
| Manufaktur | ✅ | ✅ | ✅ |
| Transaksi | ✅ | ❌ | ❌ |
| Setting | ✅ | ❌ | ❌ |

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan buat pull request atau laporkan issue.

## 📄 Lisensi

Hak Cipta © 2024 **Mari Partner**. All rights reserved.

---

<p align="center">
  Dibuat dengan ❤️ oleh <strong>Mari Partner</strong>
</p>

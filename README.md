# MoneyTrack — Aplikasi Pengelolaan Keuangan Pelajar & Mahasiswa

> **Tagline:** *"Kelola Uangmu, Atur Masa Depanmu."*

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-4.4-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)
![Tests](https://img.shields.io/badge/Tests-19%20Passed-16A36A?style=for-the-badge&logo=checkmarx&logoColor=white)

---

## 1. Tentang MoneyTrack

**MoneyTrack** adalah website aplikasi pengelolaan keuangan pribadi yang dirancang secara khusus untuk memenuhi kebutuhan **pelajar dan mahasiswa**. 

Aplikasi ini memudahkan mahasiswa mencatat uang saku bulanan, beasiswa, pendapatan paruh waktu, serta pos-pos belanja penting seperti kebutuhan kuliah, makan, bensin, dan hiburan. Dilengkapi dengan visualisasi grafik interaktif, analisis rasio tabungan, dan perhitungan saldo otomatis:

$$\text{Saldo} = \text{Total Pemasukan} - \text{Total Pengeluaran}$$

Aplikasi dibangun dengan arsitektur **MVC (Model-View-Controller)** yang rapi, berkinerja tinggi, dan menerapkan standar keamanan web modern.

---

## 2. Fitur Utama Aplikasi

### A. Autentikasi Akun Aman
- **Registrasi Akun Baru:** Validasi nama, email unik, dan konfirmasi kata sandi.
- **Login Sesi:** Autentikasi aman berbasis sesi Laravel dengan fitur *Remember Me*.
- **Logout:** Menghancurkan sesi dan merestart token CSRF.
- **Enkripsi Sandi:** Menggunakan hashing **Bcrypt** standar industri.
- **Proteksi Halaman (Middleware):** Tamu tidak dapat mengakses dashboard atau halaman internal.
- **Isolasi Data (Data Privacy):** Setiap pengguna hanya dapat melihat, menambah, mengubah, dan menghapus data miliknya sendiri.

### B. Dashboard Interaktif
- **Sapaan Personal:** Ucapan selamat pagi/siang/sore/malam dinamis sesuai waktu setempat.
- **4 Kartu Metrik Finansial:**
  1. *Saldo Tersedia* (Pemasukan - Pengeluaran)
  2. *Total Pemasukan* (Arus kas masuk)
  3. *Total Pengeluaran* (Arus kas keluar)
  4. *Total Transaksi* (Banyaknya catatan keuangan)
- **Grafik Tren Arus Kas 6 Bulan Terakhir:** Visualisasi batang (bar chart) pemasukan vs pengeluaran menggunakan **Chart.js**.
- **Grafik Donat Kategori Pengeluaran:** Menampilkan proporsi pengeluaran per kategori.
- **5 Transaksi Terkini:** Tabel transaksi terbaru lengkap dengan badge jenis dan nominal berwarna.
- **Tombol Tambah Transaksi Cepat:** Modal pop-up yang dapat diakses langsung dari mana saja.

### C. Manajemen Transaksi (CRUD Lengkap)
- **Tambah Transaksi:** Menginput judul, jenis (pemasukan/pengeluaran), kategori dinamis, nominal rupiah berformat otomatis, tanggal, dan catatan opsional.
- **Lihat Transaksi:** Menampilkan daftar data lengkap dengan format mata uang Rupiah (`Rp 1.500.000`).
- **Edit Transaksi:** Memperbarui data transaksi melalui modal interaktif yang divalidasi ketat.
- **Hapus Transaksi:** Konfirmasi penghapusan aman untuk mencegah ketidaksengajaan.
- **Kategori Bawaan Mahasiswa:**
  - *Pemasukan:* Uang saku, Beasiswa, Penghasilan tambahan, Lainnya.
  - *Pengeluaran:* Makanan, Transportasi, Pendidikan, Hiburan, Kebutuhan pribadi, Lainnya.

### D. Riwayat Transaksi & Multi-Filter
- Pencarian teks bebas berdasarkan **Judul** atau **Catatan**.
- Filter berdasarkan **Jenis Transaksi** (Semua / Pemasukan / Pengeluaran).
- Filter berdasarkan **Kategori** belanja/pemasukan.
- Filter berdasarkan **Rentang Tanggal** (*Start Date* s/d *End Date*).
- Pengurutan data (*Terbaru / Terlama*).
- **Ringkasan Filter Real-time:** Menampilkan total pemasukan, pengeluaran, dan selisih dari hasil filter.
- **Paginasi Bersih:** Navigasi halaman yang mempertahankan parameter filter di URL (`withQueryString()`).

### E. Laporan Keuangan & Analisis
- Pilihan periode: **Bulan Ini**, **Bulan Lalu**, **Tahun Ini**, **Semua Waktu**, atau **Rentang Kustom**.
- Kartu ringkasan finansial periode: Total Pemasukan, Total Pengeluaran, Saldo Bersih, dan **Rasio Tabungan (%)**.
- Dua grafik analitik interaktif berbasis Chart.js.
- Tabel rincian pengeluaran per kategori lengkap dengan persentase dan progress bar.
- Tabel daftar mutasi transaksi lengkap pada periode tersebut.
- Fitur **Cetak Laporan (Print Friendly)** langsung ke PDF/Printer tanpa elemen sidebar dan tombol.

### F. Pengaturan Profil Pengguna
- Melihat nama, email, status akun, dan tanggal bergabung.
- Mengubah nama lengkap dan alamat email.
- Mengubah kata sandi dengan verifikasi kata sandi saat ini.

---

## 3. Desain UI/UX & Palet Warna

Antarmuka MoneyTrack didesain bersih, modern, dan minimalis sesuai panduan visual resmi:
- **Navy (`#172B4D`):** Navigasi sidebar, header brand, dan elemen primer.
- **Dark Gray (`#263445`):** Teks judul sekunder dan kartu aksen.
- **Green (`#16A36A`):** Indikator pemasukan, saldo positif, dan tombol aksi utama.
- **Red (`#E85D5D`):** Indikator pengeluaran, saldo defisit, dan tombol bahaya/hapus.
- **Light Gray (`#F4F6FA`):** Latar belakang canvas aplikasi yang lembut.
- **White (`#FFFFFF`):** Latar belakang kartu kontainer dan tabel.
- **Tipografi:** Menggunakan Google Font **Inter** untuk kenyamanan membaca di desktop, tablet, dan smartphone.

---

## 4. Struktur Database (`moneytrack_db`)

Database terdiri dari 3 tabel utama yang saling berelasi:

### A. Tabel `users`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `name` | VARCHAR(255) | Nama lengkap pengguna |
| `email` | VARCHAR(255) | Alamat email (Unique) |
| `password` | VARCHAR(255) | Kata sandi terenkripsi (Hash::make) |
| `created_at` | TIMESTAMP | Waktu pendaftaran |
| `updated_at` | TIMESTAMP | Waktu pembaruan profil |

### B. Tabel `categories`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `user_id` | BIGINT UNSIGNED | Foreign Key ke `users.id` (Nullable, NULL = Default Sistem) |
| `name` | VARCHAR(255) | Nama kategori |
| `type` | ENUM('income', 'expense') | Jenis kategori |
| `created_at` | TIMESTAMP | Waktu pembuatan |
| `updated_at` | TIMESTAMP | Waktu pembaruan |

### C. Tabel `transactions`
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | Primary Key, Auto Increment |
| `user_id` | BIGINT UNSIGNED | Foreign Key ke `users.id` (Cascade On Delete) |
| `category_id` | BIGINT UNSIGNED | Foreign Key ke `categories.id` |
| `title` | VARCHAR(255) | Judul/Nama transaksi |
| `amount` | DECIMAL(15, 2) | Nominal uang transaksi (Rupiah) |
| `description` | TEXT | Catatan atau rincian opsional (Nullable) |
| `transaction_date` | DATE | Tanggal transaksi dilakukan |
| `created_at` | TIMESTAMP | Waktu pencatatan |
| `updated_at` | TIMESTAMP | Waktu pembaruan |

---

## 5. Panduan Instalasi & Menjalankan Aplikasi

Ikuti langkah-langkah mudah berikut untuk menjalankan MoneyTrack di komputer lokal Anda menggunakan **XAMPP** dan **Composer**:

### Langkah 1: Siapkan XAMPP
1. Buka aplikasi **XAMPP Control Panel**.
2. Nyalakan modul **Apache** dan **MySQL** (tekan tombol *Start* pada keduanya).

### Langkah 2: Buat Database di MySQL
Buka browser dan akses **phpMyAdmin** (`http://localhost/phpmyadmin`) atau melalui terminal:
```sql
CREATE DATABASE moneytrack_db;
```

### Langkah 3: Konfigurasi File `.env`
Pastikan konfigurasi koneksi basis data di file `.env` pada folder root proyek sudah sesuai:
```env
APP_NAME=MoneyTrack
APP_ENV=local
APP_KEY=base64:g31Of4c2uK1l19rCHbvRKFpZlvwKojVn3Q2JylTrMHE=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=moneytrack_db
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 4: Jalankan Migration dan Seeder
Buka terminal (Command Prompt / PowerShell / Git Bash) di folder proyek ini, lalu jalankan:
```bash
php artisan migrate:fresh --seed
```
*Perintah ini akan membuat seluruh tabel di MySQL serta mengisi kategori bawaan mahasiswa dan 1 akun demo dengan 8 contoh transaksi realistis.*

### Langkah 5: Jalankan Server Lokal
Jalankan server pengembangan Laravel:
```bash
php artisan serve
```

Buka peramban (browser) dan akses alamat:
```
http://127.0.0.1:8000
```

---

## 6. Akun Uji Coba Bawaan (Demo Account)

Anda dapat langsung menguji aplikasi tanpa harus mendaftar akun baru:

- **URL Login:** `http://127.0.0.1:8000/login`
- **Email:** `mahasiswa@moneytrack.test`
- **Kata Sandi:** `password123`

*(Pada form login telah disediakan tombol shortcut **"Gunakan"** untuk mengisi email dan password demo secara otomatis).*

Atau Anda juga dapat menekan menu **Daftar Akun Baru** untuk membuat akun pribadi Anda sendiri.

---

## 7. Hasil Pengujian Otomatis (Automated Testing)

Aplikasi telah diuji menggunakan **PHPUnit** dengan total **19 skenario pengujian** yang mencakup seluruh aspek penting aplikasi:

```text
Tests\Feature\AuthTest
  ✓ guest is redirected to login
  ✓ user can view login page
  ✓ user can view register page
  ✓ user can register successfully
  ✓ user can login with valid credentials
  ✓ user cannot login with wrong password
  ✓ user can logout

Tests\Feature\TransactionTest
  ✓ user can create income transaction
  ✓ user can create expense transaction
  ✓ user can update their own transaction
  ✓ user cannot update another users transaction (Security 403 Forbidden)
  ✓ user cannot delete another users transaction (Security 403 Forbidden)
  ✓ user can delete their own transaction

Tests\Feature\DashboardAndReportTest
  ✓ dashboard calculates balance accurately (Saldo = Pemasukan - Pengeluaran)
  ✓ history page filters by type
  ✓ report page loads successfully
  ✓ user can update profile and password

Tests\Feature\ExampleTest
  ✓ root redirects to login for guests

-------------------------------------------------------
Hasil Pengujian: 19 Passed (52 assertions) — 100% SUKSES
```

Untuk menjalankan kembali seluruh pengujian otomatis:
```bash
php artisan test
```

---

## 8. Struktur Direktori Utama Proyek

```text
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php          # Logika login, registrasi, logout
│   │   ├── DashboardController.php     # Ringkasan saldo, tren 6 bulan, donat kategori
│   │   ├── TransactionController.php   # CRUD transaksi & modal edit/delete
│   │   ├── HistoryController.php       # Riwayat, pencarian teks, multi-filter
│   │   ├── ReportController.php        # Laporan keuangan dinamis, Chart.js, cetak
│   │   └── ProfileController.php       # Ubah nama, email, dan kata sandi
│   └── Models/
│       ├── User.php                    # Model Pengguna & relasi
│       ├── Category.php                # Model Kategori (Income / Expense)
│       └── Transaction.php             # Model Transaksi & scope user
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2026_11_12_043320_create_categories_table.php
│   │   └── 2026_11_12_043322_create_transactions_table.php
│   └── seeders/
│       ├── CategorySeeder.php          # 10 Kategori bawaan mahasiswa
│       └── DatabaseSeeder.php          # Seeder lengkap + akun demo
├── public/
│   ├── css/
│   │   └── moneytrack.css              # Style guide resmi, palet navy/green/red
│   └── js/
│       └── moneytrack.js               # Format rupiah, switch kategori dinamis, modal
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php           # Template utama (Sidebar, Navbar, Footer, Modal)
│       │   └── auth.blade.php          # Template halaman login & registrasi
│       ├── auth/
│       │   ├── login.blade.php         # Form login + demo account loader
│       │   └── register.blade.php      # Form registrasi mahasiswa baru
│       ├── dashboard.blade.php         # Dashboard metrik & 2 grafik Chart.js
│       ├── transactions/
│       │   ├── index.blade.php         # Manajemen transaksi & CRUD modal
│       │   └── history.blade.php       # Halaman riwayat lengkap dengan filter komprehensif
│       ├── reports/
│       │   └── index.blade.php         # Laporan keuangan, tren, persentase belanja & print
│       └── profile/
│           └── index.blade.php         # Pengaturan identitas dan kata sandi
├── routes/
│   └── web.php                         # Definisi seluruh rute aplikasi yang diproteksi
└── tests/
    └── Feature/                        # 19 skenario feature test lengkap
```

---

## 9. Hak Cipta & Lisensi

Dikembangkan dengan dedikasi untuk mata kuliah Rekayasa Perangkat Lunak (PPL) / Pemrograman Web — Semester 5.  
Didesain untuk menjadi solusi keuangan terbaik bagi pelajar dan mahasiswa di Indonesia.

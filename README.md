# 📝 ContentEase — Sistem Manajemen Konten

> Aplikasi web berbasis **Laravel** untuk mengelola konten secara mudah, terstruktur, dan efisien.

---

## 📌 Deskripsi

**ContentEase** adalah sistem manajemen konten (CMS) yang dibangun menggunakan framework Laravel. Aplikasi ini dirancang untuk mempermudah pengelolaan konten digital secara terpusat, mulai dari pembuatan, pengeditan, hingga penerbitan konten.

---

## ✨ Fitur Utama

- 🔐 **Autentikasi Pengguna** — Sistem login dan manajemen sesi yang aman
- 📄 **Manajemen Konten** — Buat, edit, dan hapus konten dengan mudah
- 🗂️ **Organisasi Konten** — Pengelompokan dan kategorisasi konten
- 📱 **Tampilan Responsif** — Antarmuka yang nyaman di berbagai perangkat
- ⚡ **Build Modern** — Menggunakan Vite untuk asset bundling yang cepat
- 🧪 **Testing** — Dilengkapi dengan PHPUnit untuk pengujian otomatis

---

## 🛠️ Teknologi yang Digunakan

| Teknologi | Versi | Keterangan |
|-----------|-------|-----------|
| PHP | ^8.2 | Bahasa pemrograman utama |
| Laravel | ^12.0 | Framework backend |
| Blade | — | Template engine Laravel |
| CSS | — | Styling antarmuka |
| JavaScript | — | Interaksi frontend |
| Vite | — | Asset bundler & dev server |
| Laravel Tinker | ^2.10.1 | REPL untuk debugging |
| PHPUnit | ^11.5.3 | Framework testing |

---

## 🗂️ Struktur Direktori

```
ContentEase-Manajemen-Konten/
├── app/                    # Logika utama aplikasi (Models, Controllers, dll.)
├── bootstrap/              # File bootstrap Laravel
├── config/                 # Konfigurasi aplikasi
├── database/               # Migration, Seeder, dan Factory
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/                 # Entry point & asset publik
├── resources/              # View (Blade), CSS, dan JS
│   ├── css/
│   ├── js/
│   └── views/
├── routes/                 # Definisi routing aplikasi
│   └── web.php
├── storage/                # File yang di-generate aplikasi (logs, cache)
├── tests/                  # Unit & Feature tests
├── .env.example            # Contoh konfigurasi environment
├── artisan                 # CLI Laravel
├── composer.json           # Dependensi PHP
├── package.json            # Dependensi Node.js
├── vite.config.js          # Konfigurasi Vite
└── phpunit.xml             # Konfigurasi PHPUnit
```

---

## ⚙️ Cara Instalasi

### Prasyarat

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL / MariaDB / SQLite

### Langkah-langkah

1. **Clone repository ini**
   ```bash
   git clone https://github.com/sahal-shinee/ContentEase-Manajemen-Konten.git
   cd ContentEase-Manajemen-Konten
   ```

2. **Jalankan setup otomatis** (install semua dependensi sekaligus)
   ```bash
   composer run setup
   ```

   Atau lakukan secara manual:

   ```bash
   # Install dependensi PHP
   composer install

   # Salin file environment
   cp .env.example .env

   # Generate application key
   php artisan key:generate

   # Jalankan migrasi database
   php artisan migrate

   # Install dependensi Node.js
   npm install

   # Build asset frontend
   npm run build
   ```

3. **Konfigurasi file `.env`**
   ```env
   APP_NAME=ContentEase
   APP_URL=http://localhost

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=contentease
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Jalankan aplikasi**
   ```bash
   composer run dev
   ```
   Perintah ini menjalankan sekaligus: PHP server, queue listener, log viewer (Pail), dan Vite dev server.

   Atau jalankan hanya PHP server:
   ```bash
   php artisan serve
   ```

5. **Akses aplikasi** di browser
   ```
   http://localhost:8000
   ```

---

## 🧪 Menjalankan Tests

```bash
composer run test
```

Atau langsung dengan Artisan:
```bash
php artisan test
```

---

## 🔑 Perintah Artisan Berguna

```bash
# Migrasi ulang database + seeder
php artisan migrate:fresh --seed

# Bersihkan cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Buka Tinker (REPL)
php artisan tinker

# Lihat semua route
php artisan route:list
```

---

## 👨‍💻 Developer

Dikembangkan oleh **Muhamad Sahal Nurjamil**

[![Instagram](https://img.shields.io/badge/Instagram-@sahaljm__-E4405F?style=flat&logo=instagram)](https://www.instagram.com/sahaljm_/)

---

## 📄 Lisensi

Proyek ini menggunakan lisensi **MIT**. Silakan lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.

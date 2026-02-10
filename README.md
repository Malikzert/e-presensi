<p align="center"><a href="https://github.com/Malikzert/e-presensi" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<img src="https://img.shields.io/github/languages/top/Malikzert/e-presensi?color=red" alt="Top Language">
<img src="https://img.shields.io/github/last-commit/Malikzert/e-presensi" alt="Last Commit">
<img src="https://img.shields.io/github/license/Malikzert/e-presensi" alt="License">
</p>

## Tentang E-Presensi

**E-Presensi** adalah sistem manajemen absensi berbasis web yang dibangun menggunakan framework **Laravel**. Proyek ini dirancang untuk memudahkan pencatatan kehadiran secara digital menggunakan fitur lokasi (Maps) dan notifikasi email.

---

## 🛠 Tech Stack & Tools

### Core Framework & Language
<p align="left">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" />
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
</p>

### Service & API Integration
<p align="left">
  <img src="https://img.shields.io/badge/Google_Maps_API-4285F4?style=for-the-badge&logo=googlemaps&logoColor=white" />
  <img src="https://img.shields.io/badge/Gmail_SMTP-D14836?style=for-the-badge&logo=gmail&logoColor=white" />
  <img src="https://img.shields.io/badge/Google_Cloud-4285F4?style=for-the-badge&logo=googlecloud&logoColor=white" />
</p>

### Development Tools
<p align="left">
  <img src="https://img.shields.io/badge/Visual_Studio_Code-007ACC?style=for-the-badge&logo=visual-studio-code&logoColor=white" />
  <img src="https://img.shields.io/badge/Git-F05032?style=for-the-badge&logo=git&logoColor=white" />
  <img src="https://img.shields.io/badge/GitHub-181717?style=for-the-badge&logo=github&logoColor=white" />
  <img src="https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white" />
  <img src="https://img.shields.io/badge/NPM-CB3837?style=for-the-badge&logo=npm&logoColor=white" />
</p>

---

## ✨ Fitur Utama

- **Pencatatan Real-time**: Absensi cepat dengan validasi waktu server.
- **Geolocation (Google Maps)**: Memastikan pengguna melakukan presensi di lokasi yang ditentukan.
- **Email Notification**: Notifikasi otomatis via Gmail SMTP saat berhasil presensi.
- **Antarmuka Responsif**: Nyaman diakses melalui smartphone maupun laptop.

## 🚀 Cara Instalasi Lokal

1. **Clone repository**:
   ```bash
   git clone [https://github.com/Malikzert/e-presensi.git](https://github.com/Malikzert/e-presensi.git)
   cd e-presensi

```

2. **Instal dependensi**:
```bash
composer install
npm install && npm run dev

```


3. **Konfigurasi Environment**:
Salin `.env.example` menjadi `.env` dan masukkan kredensial Database, Google Maps API Key, dan SMTP Email Anda.
```bash
cp .env.example .env

```


4. **Persiapan Database**:
```bash
php artisan key:generate
php artisan migrate

```


5. **Jalankan Aplikasi**:
```bash
php artisan serve

```



## 📄 Lisensi

Aplikasi ini berada di bawah lisensi [MIT](https://opensource.org/licenses/MIT).

```
**Apakah ada logo library khusus (seperti Bootstrap atau Tailwind) yang ingin kamu masukkan juga?** Saya bisa tambahkan barisnya!

```

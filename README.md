# 🛍️ Preloved.U — Marketplace Barang Bekas Mahasiswa

Platform jual-beli barang bekas khusus mahasiswa di lingkungan kampus.

## 📌 Latar Belakang

Jual-beli barang bekas (buku, elektronik, perabot kos) di kalangan mahasiswa selama ini masih mengandalkan story WhatsApp yang cepat hilang, tidak terorganisir, dan susah dicari kembali. Preloved.U hadir sebagai tempat terpusat untuk menjual dan mencari barang bekas.

## 🎯 Target Pengguna

Mahasiswa (sebagai penjual maupun pembeli) di lingkungan kampus.

## 💡 Solusi

Marketplace khusus mahasiswa untuk memposting, mencari, dan menghubungi penjual barang bekas, lengkap dengan kategori, status, dan detail kondisi barang.

## ✅ Fitur yang Sudah Ada
 
- Daftar akun dan login (wajib punya akun sebelum memposting barang)
- Post barang: nama, merk, ukuran, kondisi, harga, kategori, status, nomor WA, deskripsi, foto
- Status barang: Tersedia / Nego / Terjual
- Pencarian dan filter kategori (Buku, Elektronik, Perabot Kos, Favorit)
- Detail barang: harga, merk, ukuran, kondisi, kategori, deskripsi
- Wishlist (simpan barang favorit)
- Hubungi penjual lewat WhatsApp


## Sprint 04 (Server-Side dengan PHP)
 
- Halaman utama dibuat oleh PHP (`index.php`), bukan ditulis satu per satu di HTML
- Data barang dibaca server dari `barang.json` menjadi array PHP
- Pencarian dan kategori diproses di server lewat URL (`?q=...&kategori=...`)
- Loop `foreach` untuk mencetak kartu barang dan chip kategori
- Conditional untuk status barang, foto/placeholder, dan pesan kosong
- Function: `formatRupiah()`, `filterBarang()`, `renderKartu()`, `bacaBarang()`, dll (`functions.php`)
- Output HTML di-escape dengan `htmlspecialchars` untuk mencegah XSS
- Pesan error dari server kalau `barang.json` tidak ada atau rusak

## 🚧 Belum Ada / Rencana

- Backend sungguhan dan database (barang masih dari file JSON lokal, posting & akun masih di localStorage browser)
- Login yang aman (password di-hash di server)
- Verifikasi email kampus
- Rating sederhana setelah transaksi
- Chat langsung di platform (fitur lanjutan)
- Deployment

## 🛠️ Tech Stack

- **Frontend:** HTML (semantic), CSS (responsive), JavaScript (DOM & event handling)
- **Backend:** belum (rencana: PHP / Laravel)
- **Database:** belum (rencana: MySQL)
- **Version Control:** Git & GitHub

## ▶️ Cara Menjalankan
 
Halaman ini memakai PHP, jadi **harus dijalankan lewat server PHP**. Membuka `index.php` dengan dobel klik atau dengan ekstensi Live Server **tidak akan menjalankan PHP** (yang tampil kode mentah atau halaman kosong).
 
**Opsi 1 — XAMPP**
1. Salin folder project ke `C:\xampp\htdocs\`
2. Jalankan **Apache** dari XAMPP Control Panel
3. Buka `http://localhost/NAMA_FOLDER/index.php`
**Opsi 2 — Laragon**
1. Salin folder project ke `C:\laragon\www\`
2. Klik **Start All**, lalu buka `http://localhost/NAMA_FOLDER/`
**Opsi 3 — PHP CLI (kalau PHP sudah terpasang)**
Buka terminal di folder project, lalu jalankan:
```
php -S localhost:8000
```
Buka `http://localhost:8000` di browser.
 
> **Catatan:** Akun, barang yang diposting, dan favorit disimpan di `localStorage` browser masing-masing, jadi hanya berlaku di perangkat yang dipakai. Ini simulasi untuk tahap sebelum database.

## 📂 Struktur Folder

```
├── index.php        # halaman utama (PHP + HTML)
├── functions.php    # variable, array, dan function PHP
├── barang.json      # data barang (dibaca PHP)
├── style.css        # tampilan dan responsive layout
├── script.js        # interaksi (popup, favorit, akun, post barang)
├── img/             # foto barang
└── README.md
```


## 👥 Tim Pengembang

| Nama | NIM |
|---|---|
| Mukhammad Farhan Prayoga | 220 |
| Zinedine Rivan Abdus Syukur | 364 |
| Rafi Chesta Adabi | 376 |

## 📅 Progres

| Pertemuan | Sprint | Status |
|---|---|---|
| 1 | Kickoff: Product Canvas dan repository | Selesai |
| 2 | Sprint 01: HTML semantic dan CSS responsive | Selesai |
| 3 | Sprint 02: interaksi dengan JavaScript | Selesai |
| 4 | Sprint 03: Fetch API, loading & error state | Selesai |
| 5 | Sprint 04: server-side dengan PHP | Selesai |

## 📄 Mata Kuliah

Pemrograman Web, Teknik Informatika
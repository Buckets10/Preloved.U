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

## Sprint 05 (Pertemuan 6: Object-Oriented Programming dengan PHP)

Implementasi konsep OOP (Pemrograman Berorientasi Objek) pada PHP untuk pengelolaan data dan konten dinamis:

### 🧩 Task 01: Create a Class (CLASS → OBJECT)
- Membuat class `Barang` (`Barang.php`) yang merepresentasikan entitas data barang di marketplace Preloved.U.
- Menentukan property data: `$id`, `$nama`, `$harga`, `$kategori`, `$status`, `$wa`, `$merk`, `$ukuran`, `$kondisi`, `$deskripsi`, `$foto`.
- Instansiasi object dilakukan saat data dibaca dari `barang.json` melalui `bacaBarang()`, menghasilkan kumpulan object `Barang`.
- Data barang dapat diakses dengan aman melalui getter methods.

### ⚙️ Task 02: Add Properties & Methods (OBJECT = DATA + BEHAVIOR)
- Menambahkan method/behavior pada class `Barang`:
  - Getter: `getId()`, `getNama()`, `getHarga()`, `getKategori()`, `getStatus()`, `getWa()`, `getMerk()`, `getUkuran()`, `getKondisi()`, `getDeskripsi()`, `getFoto()`.
  - Format & Tampilan: `getHargaFormat()` (format Rupiah), `getLabelStatus()`, `getLabelKategori()`, `getEmojiKategori()`, `hasFoto()`, `isTerjual()`.
  - Integrasi Komunikasi: `getLinkWa()` (menghasilkan tautan langsung WhatsApp dengan template pesan).
  - Enkapsulasi Logika: method `cocok($q, $kategori)` untuk memproses pencarian kata kunci dan filter kategori langsung di level object.
  - Serialisasi: `toArray()` dan `jsonSerialize()` (`JsonSerializable`) agar sinkronisasi data ke `script.js` tetap berjalan mulus.

### 🎨 Task 03: Generate Dynamic Content with Object (OBJECT → PHP → HTML)
- Halaman `index.php` memproses data langsung dari object `Barang`.
- Menghasilkan kartu produk secara dinamis menggunakan method `$item->renderKartu($modeFavorit)`.
- Semua data tampilan (judul, harga, status, badge, gambar/placeholder, atribut data) bersumber dari method object.
- Tampilan tetap konsisten dan presisi sesuai desain, dan interaksi frontend (dialog detail, favorit, tambah postingan) tetap berfungsi penuh.

## 🚧 Belum Ada / Rencana

- Backend database sungguhan (MySQL / PostgreSQL)
- Login yang aman (password di-hash di server dengan session/JWT)
- Verifikasi email kampus
- Rating sederhana setelah transaksi
- Chat langsung di platform (fitur lanjutan)
- Deployment

## 🛠️ Tech Stack

- **Frontend:** HTML (semantic), CSS (responsive), JavaScript (DOM & event handling)
- **Backend:** PHP (OOP - Object Oriented Programming)
- **Database:** JSON (rencana: MySQL)
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
├── index.php        # halaman utama (PHP + HTML, render dinamis dari object)
├── functions.php    # variable, array, konstanta, dan helper PHP
├── Barang.php       # class Barang (OOP: property, method, dynamic render)
├── barang.json      # data barang (sumber data object)
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
| 6 | Sprint 05: Object-Oriented Programming (OOP) dengan PHP | Selesai |

## 📄 Mata Kuliah

Pemrograman Web, Teknik Informatika
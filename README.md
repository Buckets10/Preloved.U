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

## Sprint 06 (Week 7: Dari OOP menjadi MVC dengan PHP)

Implementasi konsep **Model–View–Controller (MVC)** dalam pengembangan aplikasi web menggunakan PHP. Sprint ini berfokus pada pemisahan tanggung jawab antara pengelolaan data, tampilan, dan alur proses aplikasi agar kode lebih terstruktur, mudah dikelola, dan dikembangkan.

### 🧩 Task 01: Design the MVC Structure

- Memahami konsep arsitektur Model–View–Controller (MVC).
- Memisahkan struktur aplikasi menjadi tiga komponen utama: Model, View, dan Controller.
- Menentukan tanggung jawab masing-masing komponen.
- Membuat struktur folder dan file untuk Model, View, dan Controller.

**Implementasi di Preloved.U:**

| Komponen | File | Tanggung jawab |
|---|---|---|
| Model | `Model/Barang.php` | Data barang (property private, constructor, getter), aturan bisnis (`isTerjual()`, `hasFoto()`, `getLinkWa()`, `cocok()`), dan data access (`Barang::semua()` membaca `barang.json`, `Barang::saring()` memfilter data) |
| View | `View/barang-page.php`, `View/kartu-barang.php`, `View/dialogs.php` | Hanya menampilkan data sebagai HTML: halaman utama, kartu barang, dan dialog |
| Controller | `Controller/BarangController.php` | Menerima request (`?q=` dan `?kategori=`), meminta data ke Model, lalu mengirimkannya ke View |

### ⚙️ Task 02: Build the Controller

- Membuat Controller untuk menangani request dari pengguna.
- Mengatur alur proses aplikasi melalui Controller.
- Membuat method untuk menjalankan proses aplikasi.
- Menghubungkan Controller dengan Model.
- Mengirimkan data dari Controller menuju View.

**Implementasi:** `BarangController::index()` membaca input URL, memanggil `Barang::semua()` dan `Barang::saring()`, lalu mengirim hasilnya ke View lewat method `render()`. Jika `barang.json` tidak ditemukan atau rusak, Controller menyiapkan pesan error untuk View.

### 🔗 Task 03: Connect Model, Controller & View

- Menghubungkan Model, Controller, dan View agar dapat bekerja secara terintegrasi.
- Model bertanggung jawab mengelola data aplikasi.
- Controller menangani request dan memproses data melalui Model.
- View menerima data dari Controller dan menampilkannya sebagai halaman HTML.
- Memastikan data dapat ditampilkan melalui alur MVC tanpa error.

**Implementasi:** `index.php` hanya memanggil `BarangController`. Method `renderKartu()` yang sebelumnya berada di class `Barang` (Week 6) dipindahkan ke `View/kartu-barang.php`, sehingga Model tidak lagi berisi HTML.

### 🎨 Task 04: Demonstrate MVC Flow

- Menjalankan aplikasi dengan struktur MVC.
- Memahami alur komunikasi User → Request → Controller → Model → View.
- Menampilkan data yang berasal dari Model melalui View.
- Menjelaskan fungsi dan tanggung jawab masing-masing komponen MVC.

**Alur request:**

```
User → index.php → BarangController::index()
                        │
                        ├─ ambil input URL (q, kategori)
                        ├─ Barang::semua()  → barang.json → object Barang
                        ├─ Barang::saring() → filter kata kunci + kategori
                        ▼
                   View/barang-page.php → kartu-barang.php → HTML → User
```

### ✅ Task 05: Review & Completion Checklist

- Memastikan konsep MVC diterapkan dengan benar.
- Memastikan Model memiliki tanggung jawab yang jelas.
- Memastikan View berfokus pada tampilan.
- Memastikan Controller menangani alur aplikasi.
- Memastikan data tidak tercampur dengan tampilan.
- Menguji aplikasi agar berjalan tanpa error.
- Melakukan commit dan push hasil pekerjaan ke GitHub.

**Output:** Aplikasi web berbasis MVC dengan pemisahan Model, View, dan Controller, alur request yang terstruktur, serta hasil implementasi dan dokumentasi melalui GitHub.

## 🚧 Belum Ada / Rencana

- Backend database sungguhan (MySQL / PostgreSQL)
- Login yang aman (password di-hash di server dengan session/JWT)
- Verifikasi email kampus
- Rating sederhana setelah transaksi
- Chat langsung di platform (fitur lanjutan)
- Deployment

## 🛠️ Tech Stack

- **Frontend:** HTML (semantic), CSS (responsive), JavaScript (DOM & event handling)
- **Backend:** PHP (OOP – Object Oriented Programming, arsitektur MVC)
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
Preloved.U/
├── index.php                  # pintu masuk: memanggil BarangController
├── functions.php              # helper tampilan: e(), formatRupiah(), urlFilter(), pesanKosong()
├── Model/
│   └── Barang.php             # MODEL: class Barang (data, aturan bisnis, akses barang.json)
├── View/
│   ├── barang-page.php        # VIEW: halaman utama
│   ├── kartu-barang.php       # VIEW: satu kartu barang
│   └── dialogs.php            # VIEW: dialog detail, daftar, login, post barang
├── Controller/
│   └── BarangController.php   # CONTROLLER: request → Model → View
├── barang.json                # data barang (sumber data object)
├── style.css                  # tampilan dan responsive layout
├── script.js                  # interaksi (popup, favorit, akun, post barang)
├── img/                       # foto barang
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
| 7 | Sprint 06: Dari OOP menjadi MVC | Selesai |

## 📄 Mata Kuliah

Pemrograman Web, Teknik Informatika
# 🛍️ Preloved.U — Marketplace Barang Bekas Mahasiswa

Platform jual-beli barang bekas khusus mahasiswa di lingkungan kampus.

## 📌 Latar Belakang

Jual-beli barang bekas (buku, elektronik, perabot kos) di kalangan mahasiswa selama ini masih mengandalkan story WhatsApp yang cepat hilang, tidak terorganisir, dan susah dicari kembali. Preloved.U hadir sebagai tempat terpusat untuk menjual dan mencari barang bekas.

## 🎯 Target Pengguna

Mahasiswa (sebagai penjual maupun pembeli) di lingkungan kampus.

## 💡 Solusi

Marketplace khusus mahasiswa untuk memposting, mencari, dan menghubungi penjual barang bekas, lengkap dengan kategori, status, dan detail kondisi barang.

## ✅ Fitur yang Sudah Ada (Sprint 02)

- Daftar akun dan login (wajib punya akun sebelum memposting barang)
- Post barang: nama, merk, ukuran, kondisi, harga, kategori, status, nomor WA, deskripsi, foto
- Status barang: Tersedia / Nego / Terjual
- Pencarian barang (hasil berubah langsung saat mengetik)
- Filter kategori: Buku, Elektronik, Perabot Kos, Favorit
- Detail barang: harga, merk, ukuran, kondisi, kategori, deskripsi
- Wishlist (simpan barang favorit)
- Hubungi penjual lewat WhatsApp

## ✅ Fitur Tambahan (Sprint 03)

- Data barang diambil lewat Fetch API dari `barang.json` (`fetch` + `async/await`)
- Loading state saat data sedang diambil
- Error handling + tombol "Coba Lagi" kalau pengambilan data gagal

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

Halaman ini mengambil data lewat `fetch()`. Kalau `Preloved.U.html` dibuka langsung dengan klik dua kali (`file://`), browser memblokir `fetch()`, sehingga aplikasi hanya memakai data cadangan yang ada di dalam HTML. Supaya Fetch API benar-benar berjalan, jalankan lewat server lokal:

**Opsi 1 — VS Code Live Server (termudah)**
1. Install ekstensi "Live Server" di VS Code
2. Klik kanan `Preloved.U.html` → "Open with Live Server"

**Opsi 2 — Python**
```bash
python -m http.server
```
Lalu buka `http://localhost:8000/Preloved.U.html` di browser.

Pastikan folder `img/` dan file `barang.json` ikut ter-upload ke GitHub.

> **Catatan:** Akun, barang yang diposting, dan favorit saat ini disimpan di `localStorage` browser masing-masing, jadi hanya berlaku di perangkat yang dipakai. Ini simulasi untuk tahap frontend dan akan diganti database saat masuk materi backend.

## 📂 Struktur Folder

```
├── Preloved.U.html  # struktur halaman
├── style.css        # tampilan dan responsive layout
├── script.js        # interaksi + fetch API
├── barang.json      # data barang (sumber fetch)
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

## 📄 Mata Kuliah

Pemrograman Web, Teknik Informatika
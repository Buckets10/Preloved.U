# Preloved.U — Marketplace Barang Bekas Mahasiswa

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
- Pencarian barang (hasil berubah langsung saat mengetik)
- Filter kategori: Buku, Elektronik, Perabot Kos, Favorit
- Detail barang: harga, merk, ukuran, kondisi, kategori, deskripsi
- Wishlist (simpan barang favorit)
- Hubungi penjual lewat WhatsApp

## 🚧 Belum Ada / Rencana

- Backend dan database (data saat ini hanya tersimpan di browser)
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

1. Clone repository ini
2. Pastikan folder `img/` berisi semua gambar barang
3. Buka `index.html` di browser

Tidak perlu instalasi tambahan.

> **Catatan:** Akun, barang yang diposting, dan favorit saat ini disimpan di `localStorage` browser masing-masing, jadi hanya berlaku di perangkat yang dipakai. Ini simulasi untuk tahap frontend dan akan diganti database saat masuk materi backend.

## 📂 Struktur Folder

```
├── index.html     # struktur halaman
├── style.css      # tampilan dan responsive layout
├── script.js      # interaksi (cari, kategori, detail, akun, post barang)
├── img/           # foto barang
└── README.md
```

## 👥 Tim Pengembang

| Nama |
| _Mukhammad Farhan Prayoga_ | _220_ |
| _Zinedine Rivan Abdus Syukur_ | _364_ |
| _Rafi Chesta Adabi_ | _376_ |

## 📅 Progres

| Pertemuan | Sprint | Status |
| 1 | Kickoff: Product Canvas dan repository | Selesai |
| 2 | Task 2 : HTML semantic dan CSS responsive | Selesai |
| 3 | Task 3 : interaksi dengan JavaScript | Selesai |

## 📄 Mata Kuliah

Pemrograman Web, Teknik Informatika

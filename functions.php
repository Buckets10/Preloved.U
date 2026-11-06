<?php
/**
 * functions.php
 * Helper kecil untuk VIEW (tampilan). Tidak ada logika data di sini.
 */

// Keamanan output: cegah XSS
function e(string $teks): string
{
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}

// formatRupiah(50000) -> "Rp 50.000"
function formatRupiah(float $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Alamat link filter kategori + pencarian
function urlFilter(string $kategori, string $q): string
{
    $param = [];

    if ($kategori !== 'semua') {
        $param['kategori'] = $kategori;
    }
    if ($q !== '') {
        $param['q'] = $q;
    }

    return 'index.php' . ($param ? '?' . http_build_query($param) : '');
}

// Pesan saat daftar kosong
function pesanKosong(string $kategori, string $q): string
{
    if ($kategori === 'favorit' && $q === '') {
        return 'Belum ada barang favorit. Klik ♡ pada barang untuk menyimpannya.';
    }

    return 'Barang tidak ditemukan. Coba kata kunci atau kategori lain.';
}
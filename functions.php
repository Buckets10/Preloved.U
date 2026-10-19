<?php
/**
 * functions.php
 * Kumpulan variable, array, dan function PHP untuk Preloved.U.
 * Dipanggil oleh index.php lewat require_once.
 */

// ===== KONSTANTA (nilai yang tidak berubah) =====
const FILE_BARANG = __DIR__ . '/barang.json';

// ===== ARRAY ASOSIATIF (kode => label) =====
const NAMA_STATUS = [
    'tersedia' => 'Tersedia',
    'nego'     => 'Nego',
    'terjual'  => 'Terjual',
];

const KATEGORI = [
    'semua'      => ['label' => 'Semua',       'emoji' => ''],
    'buku'       => ['label' => 'Buku',        'emoji' => '📚'],
    'elektronik' => ['label' => 'Elektronik',  'emoji' => '🔌'],
    'perabot'    => ['label' => 'Perabot Kos', 'emoji' => '🪑'],
    'favorit'    => ['label' => 'Favorit',     'emoji' => '♥'],
];

// ===== FUNCTION: keamanan output =====
// Semua teks yang dicetak ke HTML dilewatkan ke sini supaya karakter
// seperti < dan " tidak bisa dipakai menyisipkan kode (XSS).
function e(string $teks): string
{
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}

// ===== FUNCTION: format harga =====
// Contoh: formatRupiah(50000) -> "Rp 50.000"
function formatRupiah(float $angka): string
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// ===== REQUIRE CLASS BARANG (OOP - Task 01 & Task 02) =====
require_once __DIR__ . '/Barang.php';

// ===== FUNCTION: baca data dari barang.json =====
// Mengembalikan array of Barang (Object), atau null kalau file tidak ada / isinya rusak.
function bacaBarang(): ?array
{
    if (!is_readable(FILE_BARANG)) {
        return null;
    }

    $data = json_decode((string) file_get_contents(FILE_BARANG), true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
        return null;
    }

    // Task 01: Instansiasi object dari Class Barang
    $daftarBarang = [];
    foreach ($data as $item) {
        if (is_array($item)) {
            $daftarBarang[] = new Barang($item);
        }
    }

    return $daftarBarang;
}

// ===== FUNCTION: ambil input dari URL dengan aman =====
// $_GET bisa berisi array (misal ?q[]=a). Hanya teks biasa yang diterima.
function ambilGet(string $kunci, string $bawaan = ''): string
{
    $nilai = $_GET[$kunci] ?? $bawaan;

    return is_string($nilai) ? trim($nilai) : $bawaan;
}

// ===== FUNCTION: pastikan kategori dari URL valid =====
function kategoriValid(string $kode): string
{
    return array_key_exists($kode, KATEGORI) ? $kode : 'semua';
}

// ===== FUNCTION: filter barang (pencarian + kategori) =====
// Menggunakan method cocok() yang terenkapsulasi di dalam object Barang (Task 02)
function filterBarang(array $barang, string $q, string $kategori): array
{
    $hasil = [];

    foreach ($barang as $item) {
        if ($item instanceof Barang) {
            if ($item->cocok($q, $kategori)) {
                $hasil[] = $item;
            }
        } elseif (is_array($item)) {
            $obj = new Barang($item);
            if ($obj->cocok($q, $kategori)) {
                $hasil[] = $obj;
            }
        }
    }

    return $hasil;
}

// ===== FUNCTION: buat alamat link filter =====
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

// ===== FUNCTION: pesan saat daftar kosong =====
function pesanKosong(string $kategori, string $q): string
{
    if ($kategori === 'favorit' && $q === '') {
        return 'Belum ada barang favorit. Klik ♡ pada barang untuk menyimpannya.';
    }

    return 'Barang tidak ditemukan. Coba kata kunci atau kategori lain.';
}

// ===== FUNCTION: cetak HTML satu kartu barang =====
// Delegasi ke method renderKartu() pada object Barang (Task 03)
function renderKartu($item, bool $sembunyi = false): void
{
    if ($item instanceof Barang) {
        $item->renderKartu($sembunyi);
    } elseif (is_array($item)) {
        (new Barang($item))->renderKartu($sembunyi);
    }
}
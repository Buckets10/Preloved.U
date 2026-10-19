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

// ===== FUNCTION: baca data dari barang.json =====
// Mengembalikan array barang, atau null kalau file tidak ada / isinya rusak.
function bacaBarang(): ?array
{
    if (!is_readable(FILE_BARANG)) {
        return null;
    }

    $data = json_decode((string) file_get_contents(FILE_BARANG), true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
        return null;
    }

    return $data;
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
// Proses ini dikerjakan di SERVER, jadi browser hanya menerima hasil akhirnya.
function filterBarang(array $barang, string $q, string $kategori): array
{
    $hasil = [];

    foreach ($barang as $item) {
        $labelKategori = KATEGORI[$item['kategori'] ?? '']['label'] ?? '';
        $teks = ($item['nama'] ?? '') . ' ' . ($item['deskripsi'] ?? '') . ' ' . $labelKategori;

        $cocokKata = ($q === '') || stripos($teks, $q) !== false;

        // 'favorit' disimpan di browser (localStorage), jadi server tidak memfilter kategori untuk mode itu
        $cocokKategori = in_array($kategori, ['semua', 'favorit'], true)
            || ($item['kategori'] ?? '') === $kategori;

        if ($cocokKata && $cocokKategori) {
            $hasil[] = $item;
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
// PHP menghasilkan HTML-nya langsung, jadi tidak ditulis satu per satu secara manual.
function renderKartu(array $item, bool $sembunyi = false): void
{
    $id        = (int) ($item['id'] ?? 0);
    $nama      = (string) ($item['nama'] ?? '');
    $kategori  = (string) ($item['kategori'] ?? 'buku');
    $status    = (string) ($item['status'] ?? 'tersedia');
    $foto      = (string) ($item['foto'] ?? '');
    $emoji     = KATEGORI[$kategori]['emoji'] ?? '📦';
    $labelStatus = NAMA_STATUS[$status] ?? ucfirst($status);
    $terjual   = ($status === 'terjual');
    ?>
    <article class="product-card<?= $terjual ? ' product-card--terjual' : '' ?>"
             id="barang-<?= $id ?>" data-id="<?= $id ?>" data-kategori="<?= e($kategori) ?>"<?= $sembunyi ? ' hidden' : '' ?>>

      <?php if ($foto !== ''): ?>
        <img src="<?= e($foto) ?>" alt="<?= e($nama) ?>" loading="lazy">
      <?php else: ?>
        <div class="img-placeholder"><?= $emoji ?></div>
      <?php endif; ?>

      <button type="button" class="btn-wish" aria-label="Tambah ke favorit">♡</button>

      <h2><?= e($nama) ?></h2>
      <p class="price"><?= formatRupiah((float) ($item['harga'] ?? 0)) ?></p>
      <span class="status status--<?= e($status) ?>"><?= e($labelStatus) ?></span>
      <button type="button" class="btn-detail">Lihat Detail</button>
    </article>
    <?php
}
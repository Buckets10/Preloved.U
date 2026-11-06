<?php
/**
 * View/barang-page.php
 * Tampilan utama. Semua variabel dikirim oleh BarangController.
 *
 * @var string   $namaSitus
 * @var string   $tahun
 * @var string   $q
 * @var string   $kategoriAktif
 * @var bool     $modeFavorit
 * @var Barang[] $barang
 * @var string   $galat
 * @var Barang[] $hasil
 * @var int      $jumlah
 */
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../Model/Barang.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($namaSitus) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body id="top" data-kategori="<?= e($kategoriAktif) ?>" data-q="<?= e($q) ?>" data-galat="<?= $galat !== '' ? '1' : '0' ?>">

  <header>
    <div class="logo">
      <span class="logo-icon">🛍️</span>
      <span><?= e($namaSitus) ?></span>
    </div>
    <nav>
      <ul>
        <li><a href="index.php" id="nav-beranda">Beranda</a></li>
        <li><a href="#kategori" id="nav-kategori">Kategori</a></li>
        <li><a href="#" id="nav-post">Post Barang</a></li>
        <li id="li-daftar"><a href="#" id="nav-daftar">Daftar</a></li>
        <li><a href="#" id="nav-login">Login</a></li>
      </ul>
    </nav>
  </header>

  <main>

    <form class="search-filter" method="get" action="index.php" role="search">
      <label for="search" class="sr-only">Cari barang</label>
      <input type="text" id="search" name="q" value="<?= e($q) ?>" placeholder="Cari barang, misal: buku, kipas, meja...">
      <?php if ($kategoriAktif !== 'semua'): ?>
        <input type="hidden" name="kategori" value="<?= e($kategoriAktif) ?>">
      <?php endif; ?>
      <button type="submit" id="btn-cari">Cari</button>
    </form>

    <section class="chips" id="kategori" aria-label="Filter kategori">
      <?php foreach (Barang::KATEGORI as $kode => $info): ?>
        <a class="chip<?= $kode === $kategoriAktif ? ' active' : '' ?>"
           href="<?= e(urlFilter($kode, $q)) ?>"<?= $kode === $kategoriAktif ? ' aria-current="page"' : '' ?>>
          <?= $info['emoji'] !== '' ? $info['emoji'] . ' ' : '' ?><?= e($info['label']) ?>
        </a>
      <?php endforeach; ?>
    </section>

    <?php if ($galat !== ''): ?>
      <div class="error-message" role="alert">
        <p class="error-title">Gagal memuat data barang.</p>
        <p class="error-detail"><?= e($galat) ?></p>
      </div>
    <?php endif; ?>

    <section class="product-list" id="product-list">
      <h1>Barang Tersedia</h1>
      <p id="hasil-info" class="detail-meta"><?= $modeFavorit ? '' : 'Menampilkan ' . $jumlah . ' barang' ?></p>

      <?php foreach ($hasil as $item): ?>
        <?php $sembunyi = $modeFavorit; include __DIR__ . '/kartu-barang.php'; ?>
      <?php endforeach; ?>
    </section>

    <p id="empty-message" class="empty-message"<?= ($jumlah > 0 || $galat !== '') ? ' hidden' : '' ?>><?= e(pesanKosong($kategoriAktif, $q)) ?></p>
  </main>

  <footer>
    <p>&copy; <?= $tahun ?> <?= e($namaSitus) ?></p>
  </footer>

  <?php include __DIR__ . '/dialogs.php'; ?>

  <script type="application/json" id="barang-data"><?= json_encode($barang, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
  <script src="script.js"></script>
</body>
</html>
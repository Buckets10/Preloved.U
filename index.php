<?php
// ============================================================
// BAGIAN PHP: dijalankan di SERVER sebelum HTML dikirim ke browser
// ============================================================
require_once __DIR__ . '/functions.php';

// ----- VARIABLE -----
$namaSitus = 'Preloved.U';
$tahun     = date('Y');

// ----- INPUT dari URL (?q=...&kategori=...) -----
$q             = ambilGet('q');
$kategoriAktif = kategoriValid(ambilGet('kategori', 'semua'));
$modeFavorit   = ($kategoriAktif === 'favorit');

// ----- DATA: baca barang.json menjadi array -----
$barang = bacaBarang();
$galat  = '';

if ($barang === null) {            // CONDITIONAL: data gagal dibaca
    $galat  = 'File barang.json tidak ditemukan atau isinya rusak.';
    $barang = [];
}

// ----- PROSES: saring data sesuai pencarian dan kategori -----
$hasil  = filterBarang($barang, $q, $kategoriAktif);
$jumlah = count($hasil);
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

    <!-- Pencarian: dikirim ke server lewat URL (?q=...) -->
    <form class="search-filter" method="get" action="index.php" role="search">
      <label for="search" class="sr-only">Cari barang</label>
      <input type="text" id="search" name="q" value="<?= e($q) ?>" placeholder="Cari barang, misal: buku, kipas, meja...">
      <?php if ($kategoriAktif !== 'semua'): ?>
        <input type="hidden" name="kategori" value="<?= e($kategoriAktif) ?>">
      <?php endif; ?>
      <button type="submit" id="btn-cari">Cari</button>
    </form>

    <!-- Kategori: LOOP dari array KATEGORI -->
    <section class="chips" id="kategori" aria-label="Filter kategori">
      <?php foreach (KATEGORI as $kode => $info): ?>
        <a class="chip<?= $kode === $kategoriAktif ? ' active' : '' ?>"
           href="<?= e(urlFilter($kode, $q)) ?>"<?= $kode === $kategoriAktif ? ' aria-current="page"' : '' ?>>
          <?= $info['emoji'] !== '' ? $info['emoji'] . ' ' : '' ?><?= e($info['label']) ?>
        </a>
      <?php endforeach; ?>
    </section>

    <!-- Pesan error dari server (kalau barang.json bermasalah) -->
    <?php if ($galat !== ''): ?>
      <div class="error-message" role="alert">
        <p class="error-title">Gagal memuat data barang.</p>
        <p class="error-detail"><?= e($galat) ?></p>
      </div>
    <?php endif; ?>

    <!-- Daftar barang: dicetak oleh PHP lewat LOOP, bukan ditulis satu per satu -->
    <section class="product-list" id="product-list">
      <h1>Barang Tersedia</h1>
      <p id="hasil-info" class="detail-meta"><?= $modeFavorit ? '' : 'Menampilkan ' . $jumlah . ' barang' ?></p>

      <?php foreach ($hasil as $item): ?>
        <?php renderKartu($item, $modeFavorit); ?>
      <?php endforeach; ?>
    </section>

    <p id="empty-message" class="empty-message"<?= ($jumlah > 0 || $galat !== '') ? ' hidden' : '' ?>><?= e(pesanKosong($kategoriAktif, $q)) ?></p>
  </main>

  <footer>
    <p>&copy; <?= $tahun ?> <?= e($namaSitus) ?></p>
  </footer>

  <!-- Detail barang -->
  <dialog id="dialog-detail">
    <div class="dialog-body">
      <div id="detail-gambar"></div>
      <h2 id="detail-nama"></h2>
      <p class="price" id="detail-harga"></p>
      <p><span class="status" id="detail-status"></span></p>
      <dl class="spec-list">
        <div><dt>Merk</dt><dd id="detail-merk"></dd></div>
        <div><dt>Ukuran</dt><dd id="detail-ukuran"></dd></div>
        <div><dt>Kondisi</dt><dd id="detail-kondisi"></dd></div>
      </dl>
      <p class="detail-meta" id="detail-kategori"></p>
      <p id="detail-deskripsi"></p>
      <div class="dialog-actions">
        <a class="btn btn-primary" id="detail-wa" target="_blank" rel="noopener">Hubungi Penjual via WA</a>
        <button type="button" class="btn btn-ghost" data-close>Tutup</button>
      </div>
    </div>
  </dialog>

  <!-- Daftar akun -->
  <dialog id="dialog-daftar">
    <form id="form-daftar" class="dialog-body">
      <h2>Buat Akun</h2>
      <p class="detail-meta">Daftar dulu sebelum bisa login.</p>
      <label for="daftar-nama">Nama</label>
      <input type="text" id="daftar-nama" required>
      <label for="daftar-email">Email kampus</label>
      <input type="email" id="daftar-email" required>
      <label for="daftar-password">Password (min. 6 karakter)</label>
      <input type="password" id="daftar-password" required>
      <p class="form-error" id="daftar-error" role="alert"></p>
      <div class="dialog-actions">
        <button type="submit" class="btn btn-primary">Daftar</button>
        <button type="button" class="btn btn-ghost" data-close>Batal</button>
      </div>
      <p class="detail-meta">Sudah punya akun? <button type="button" class="btn-link" id="ke-login">Login</button></p>
    </form>
  </dialog>

  <!-- Login -->
  <dialog id="dialog-login">
    <form id="form-login" class="dialog-body">
      <h2>Login</h2>
      <p class="detail-meta" id="login-info">Masuk untuk memposting barang.</p>
      <label for="login-email">Email kampus</label>
      <input type="email" id="login-email" required>
      <label for="login-password">Password</label>
      <input type="password" id="login-password" required>
      <p class="form-error" id="login-error" role="alert"></p>
      <div class="dialog-actions">
        <button type="submit" class="btn btn-primary">Masuk</button>
        <button type="button" class="btn btn-ghost" data-close>Batal</button>
      </div>
      <p class="detail-meta">Belum punya akun? <button type="button" class="btn-link" id="ke-daftar">Daftar di sini</button></p>
    </form>
  </dialog>

  <!-- Post barang -->
  <dialog id="dialog-post">
    <form id="form-post" class="dialog-body">
      <h2>Post Barang</h2>
      <label for="post-nama">Nama barang</label>
      <input type="text" id="post-nama" required>
      <label for="post-merk">Merk</label>
      <input type="text" id="post-merk" placeholder="Isi &quot;Tanpa merk&quot; jika tidak ada" required>
      <label for="post-ukuran">Ukuran</label>
      <input type="text" id="post-ukuran" placeholder="Contoh: 30 cm, A5, 120 x 40 cm" required>
      <label for="post-kondisi">Kondisi barang</label>
      <select id="post-kondisi" required>
        <option>Seperti baru</option>
        <option selected>Bagus</option>
        <option>Layak pakai</option>
        <option>Ada minus</option>
      </select>
      <label for="post-harga">Harga (Rp)</label>
      <input type="number" id="post-harga" min="0" required>
      <label for="post-kategori">Kategori</label>
      <select id="post-kategori" required>
        <option value="buku">Buku</option>
        <option value="elektronik">Elektronik</option>
        <option value="perabot">Perabot Kos</option>
      </select>
      <label for="post-status">Status</label>
      <select id="post-status" required>
        <option value="tersedia">Tersedia</option>
        <option value="nego">Nego</option>
        <option value="terjual">Terjual</option>
      </select>
      <label for="post-wa">Nomor WA (format 628xxx)</label>
      <input type="tel" id="post-wa" pattern="62[0-9]{8,13}" placeholder="628123456789" required>
      <label for="post-deskripsi">Deskripsi</label>
      <textarea id="post-deskripsi" rows="3" required></textarea>
      <label for="post-foto">Foto (opsional)</label>
      <input type="file" id="post-foto" accept="image/*">
      <div class="dialog-actions">
        <button type="submit" class="btn btn-primary">Posting</button>
        <button type="button" class="btn btn-ghost" data-close>Batal</button>
      </div>
    </form>
  </dialog>

  <!-- Data barang dari PHP untuk JavaScript (dipakai popup detail dan favorit) -->
  <script type="application/json" id="barang-data"><?= json_encode($barang, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
  <script src="script.js"></script>
</body>
</html>
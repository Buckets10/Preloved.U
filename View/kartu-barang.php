<?php
/**
 * View/kartu-barang.php
 * Satu kartu barang.
 *
 * @var Barang $item
 * @var bool   $sembunyi
 */
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../Model/Barang.php';
?>
<article class="product-card<?= $item->isTerjual() ? ' product-card--terjual' : '' ?>"
         id="barang-<?= $item->getId() ?>" data-id="<?= $item->getId() ?>" data-kategori="<?= e($item->getKategori()) ?>"<?= $sembunyi ? ' hidden' : '' ?>>

  <?php if ($item->hasFoto()): ?>
    <img src="<?= e($item->getFoto()) ?>" alt="<?= e($item->getNama()) ?>" loading="lazy">
  <?php else: ?>
    <div class="img-placeholder"><?= $item->getEmojiKategori() ?></div>
  <?php endif; ?>

  <button type="button" class="btn-wish" aria-label="Tambah ke favorit">♡</button>

  <h2><?= e($item->getNama()) ?></h2>
  <p class="price"><?= e(formatRupiah($item->getHarga())) ?></p>
  <span class="status status--<?= e($item->getStatus()) ?>"><?= e($item->getLabelStatus()) ?></span>
  <button type="button" class="btn-detail">Lihat Detail</button>
</article>
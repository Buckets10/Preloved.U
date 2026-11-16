<?php
/**
 * Form bersama untuk create & edit.
 * @var string $aksi     URL tujuan form
 * @var string $metode   'POST' atau 'PUT'
 * @var string $tombol   teks tombol simpan
 * @var array  $nilai    ['nama' => ..., 'deskripsi' => ...]
 */
$galat = errors();
?>
<form method="post" action="<?= e($aksi) ?>" class="program-form" novalidate>
  <?= csrf_field() ?>
  <?php if ($metode !== 'POST'): ?><?= method_field($metode) ?><?php endif; ?>

  <label for="nama">Nama</label>
  <input type="text" id="nama" name="nama" value="<?= e(old('nama', $nilai['nama'])) ?>"
         class="<?= isset($galat['nama']) ? 'input-error' : '' ?>">
  <?php foreach ($galat['nama'] ?? [] as $pesan): ?>
    <p class="field-error" role="alert"><?= e($pesan) ?></p>
  <?php endforeach; ?>

  <label for="deskripsi">Deskripsi</label>
  <textarea id="deskripsi" name="deskripsi" rows="4"
            class="<?= isset($galat['deskripsi']) ? 'input-error' : '' ?>"><?= e(old('deskripsi', $nilai['deskripsi'])) ?></textarea>
  <?php foreach ($galat['deskripsi'] ?? [] as $pesan): ?>
    <p class="field-error" role="alert"><?= e($pesan) ?></p>
  <?php endforeach; ?>

  <div class="form-actions">
    <button type="submit" class="btn-simpan"><?= e($tombol) ?></button>
    <a href="<?= e(url('programs')) ?>" class="btn-batal">Batal</a>
  </div>
</form>

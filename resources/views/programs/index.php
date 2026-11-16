<?php /** @var \App\Models\Program[] $programs */ $judul = 'Daftar Program'; ?>
<div class="program-head">
  <h1>Daftar Program</h1>
  <a class="btn-simpan" href="<?= e(url('programs/create')) ?>">+ Tambah Program</a>
</div>

<?php if ($pesan = flash('sukses')): ?>
  <p class="alert-sukses" role="status"><?= e($pesan) ?></p>
<?php endif; ?>

<?php if (!$programs): ?>
  <p class="empty-message">Belum ada program. Klik "Tambah Program" untuk membuat yang pertama.</p>
<?php else: ?>
  <div class="program-tabel">
    <table>
      <thead>
        <tr><th>ID</th><th>Nama</th><th>Deskripsi</th><th>Aksi</th></tr>
      </thead>
      <tbody>
      <?php foreach ($programs as $program): ?>
        <tr>
          <td><?= (int) $program->id ?></td>
          <td><?= e($program->nama) ?></td>
          <td><?= e($program->deskripsi) ?></td>
          <td class="aksi">
            <a class="btn-edit" href="<?= e(url('programs/' . (int) $program->id . '/edit')) ?>">Edit</a>
            <form method="post" action="<?= e(url('programs/' . (int) $program->id)) ?>"
                  onsubmit="return confirm('Hapus program ini?')">
              <?= csrf_field() ?><?= method_field('DELETE') ?>
              <button type="submit" class="btn-hapus">Hapus</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

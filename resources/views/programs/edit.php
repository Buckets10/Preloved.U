<?php /** @var \App\Models\Program $program */ $judul = 'Edit Program'; ?>
<h1>Edit Program</h1>
<?php
$aksi   = url('programs/' . (int) $program->id);
$metode = 'PUT';
$tombol = 'Simpan Perubahan';
$nilai  = ['nama' => (string) $program->nama, 'deskripsi' => (string) $program->deskripsi];
require __DIR__ . '/_form.php';

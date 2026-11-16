<?php $judul = 'Tambah Program'; ?>
<h1>Tambah Program</h1>
<?php
$aksi   = url('programs');
$metode = 'POST';
$tombol = 'Simpan';
$nilai  = ['nama' => '', 'deskripsi' => ''];
require __DIR__ . '/_form.php';

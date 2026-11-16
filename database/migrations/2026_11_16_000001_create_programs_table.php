<?php
/**
 * Migration: membuat tabel `programs` (id, nama, deskripsi) + 2 data awal
 * sesuai contoh response di Task 03 (Pendidikan & Kesehatan).
 */
return [
    'up' => function (PDO $pdo, string $driver): void {
        $id = $driver === 'mysql'
            ? 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY'
            : 'INTEGER PRIMARY KEY AUTOINCREMENT';

        $pdo->exec("CREATE TABLE IF NOT EXISTS programs (
            id $id,
            nama VARCHAR(100) NOT NULL,
            deskripsi TEXT NOT NULL
        )");

        $jumlah = (int) $pdo->query('SELECT COUNT(*) FROM programs')->fetchColumn();
        if ($jumlah === 0) {
            $isi = $pdo->prepare('INSERT INTO programs (nama, deskripsi) VALUES (?, ?)');
            $isi->execute(['Pendidikan', 'Program di bidang pendidikan.']);
            $isi->execute(['Kesehatan', 'Program di bidang kesehatan.']);
        }
    },
    'down' => function (PDO $pdo, string $driver): void {
        $pdo->exec('DROP TABLE IF EXISTS programs');
    },
];

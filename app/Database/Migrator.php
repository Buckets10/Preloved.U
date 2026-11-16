<?php
namespace App\Database;

use PDO;

/** Menjalankan file di database/migrations/ yang belum pernah dijalankan. */
class Migrator
{
    public static function jalankan(PDO $pdo, string $driver): void
    {
        $pdo->exec('CREATE TABLE IF NOT EXISTS migrations (migration VARCHAR(255) PRIMARY KEY)');

        $sudah = $pdo->query('SELECT migration FROM migrations')->fetchAll(PDO::FETCH_COLUMN);
        $files = glob(base_path('database/migrations/*.php')) ?: [];
        sort($files);

        foreach ($files as $file) {
            $nama = basename($file, '.php');
            if (in_array($nama, $sudah, true)) {
                continue;
            }

            $migrasi = require $file;     // mengembalikan ['up' => callable, 'down' => callable]
            $migrasi['up']($pdo, $driver);

            $pdo->prepare('INSERT INTO migrations (migration) VALUES (?)')->execute([$nama]);
        }
    }
}

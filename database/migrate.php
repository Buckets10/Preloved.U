<?php
/**
 * Jalankan manual (opsional): php database/migrate.php
 * Migrasi juga otomatis berjalan saat aplikasi pertama kali membuka database.
 */
require __DIR__ . '/../app/bootstrap.php';

App\Database\Connection::get();
echo "Migrasi selesai (driver: " . App\Database\Connection::driver() . ").\n";

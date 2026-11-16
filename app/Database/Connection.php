<?php
namespace App\Database;

use PDO;

/**
 * Koneksi database (PDO) + migrasi otomatis.
 * Default memakai SQLite (tanpa setup). Untuk MySQL (XAMPP/Laragon) ubah config/database.php
 * atau set environment variable DB_CONNECTION=mysql.
 */
class Connection
{
    private static ?PDO $pdo = null;

    public static function get(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = self::buka();
            Migrator::jalankan(self::$pdo, self::driver());
        }
        return self::$pdo;
    }

    public static function driver(): string
    {
        return (string) config('database.default', 'sqlite');
    }

    private static function buka(): PDO
    {
        $driver = self::driver();
        $cfg = config('database.connections.' . $driver, []);

        if ($driver === 'mysql') {
            $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $cfg['host'], $cfg['port'], $cfg['database']);
            $pdo = new PDO($dsn, $cfg['username'], $cfg['password']);
        } else {
            $file = $cfg['database'];
            if (!is_dir(dirname($file))) {
                mkdir(dirname($file), 0775, true);
            }
            $pdo = new PDO('sqlite:' . $file);
            $pdo->exec('PRAGMA foreign_keys = ON');
        }

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }
}

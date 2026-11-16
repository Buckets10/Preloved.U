<?php
/**
 * config/database.php
 * Default: SQLite (file database/database.sqlite, dibuat otomatis, tidak perlu setup).
 * Pakai MySQL: set DB_CONNECTION=mysql lalu sesuaikan host/database/username/password
 * (buat database kosong dulu, mis. "preloved_u"; tabel dibuat otomatis).
 */
return [
    'default' => getenv('DB_CONNECTION') ?: 'sqlite',

    'connections' => [
        'sqlite' => [
            'database' => getenv('DB_DATABASE') ?: dirname(__DIR__) . '/database/database.sqlite',
        ],
        'mysql' => [
            'host'     => getenv('DB_HOST') ?: '127.0.0.1',
            'port'     => getenv('DB_PORT') ?: '3306',
            'database' => getenv('DB_DATABASE') ?: 'preloved_u',
            'username' => getenv('DB_USERNAME') ?: 'root',
            'password' => getenv('DB_PASSWORD') ?: '',
        ],
    ],
];

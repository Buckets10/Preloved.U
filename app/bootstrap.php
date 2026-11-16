<?php
/**
 * app/bootstrap.php
 * Menyiapkan autoloader (namespace App\ => folder app/), helper, dan session.
 * Dipanggil sekali dari index.php.
 */

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $file = __DIR__ . '/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require_once __DIR__ . '/../functions.php';      // e(), formatRupiah() (helper View yang sudah ada)
require_once __DIR__ . '/helpers.php';           // base_url(), view(), redirect(), response(), dll.
require_once __DIR__ . '/Support/Route.php';     // class Route (global, seperti facade Route di Laravel)

if (session_status() === PHP_SESSION_NONE && PHP_SAPI !== 'cli') {
    session_start();
}

// Flash data: pesan/error/input lama hanya hidup sampai request berikutnya.
if (!isset($_SESSION)) {
    $_SESSION = [];
}
$_SESSION['_flash']      = $_SESSION['_flash_baru'] ?? [];
$_SESSION['_flash_baru'] = [];

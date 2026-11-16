<?php
/**
 * Router untuk PHP built-in server:  php -S localhost:8000 router.php
 * File statis (css, js, img) dilayani langsung; folder kode & database diblokir;
 * sisanya diteruskan ke index.php (front controller).
 */
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('#^/(app|config|database|routes|resources|Model|Controller|\.git)(/|$)#', $path)
    || preg_match('#\.(sqlite|md)$#', $path)) {
    http_response_code(403);
    echo '403 Forbidden';
    return true;
}

if ($path !== '/' && is_file(__DIR__ . $path) && !preg_match('#\.php$#', $path)) {
    return false;   // biarkan server melayani file statis
}

require __DIR__ . '/index.php';

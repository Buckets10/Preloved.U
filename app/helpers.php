<?php
/**
 * app/helpers.php
 * Fungsi bantu global (mirip helper Laravel: view(), redirect(), response(), old(), url()).
 */

use App\Http\Response;

function base_path(string $path = ''): string
{
    return dirname(__DIR__) . ($path !== '' ? '/' . ltrim($path, '/') : '');
}

/** Folder tempat project berada di URL, mis. "/Preloved.U" atau "" (kalau di root). */
function base_url(string $path = ''): string
{
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    $dir = rtrim($dir, '/');
    return $dir . ($path !== '' ? '/' . ltrim($path, '/') : '');
}

function url(string $path = ''): string
{
    return base_url($path);
}

function config(string $key, $default = null)
{
    static $cache = [];
    $bagian = explode('.', $key);
    $file   = array_shift($bagian);

    if (!isset($cache[$file])) {
        $lokasi = base_path("config/{$file}.php");
        $cache[$file] = is_file($lokasi) ? require $lokasi : [];
    }

    $nilai = $cache[$file];
    foreach ($bagian as $b) {
        if (!is_array($nilai) || !array_key_exists($b, $nilai)) {
            return $default;
        }
        $nilai = $nilai[$b];
    }
    return $nilai;
}

/** Request yang sedang diproses (satu instance per permintaan). */
function request(): \App\Http\Request
{
    static $request = null;
    return $request ??= \App\Http\Request::capture();
}

function response(): Response
{
    return new Response();
}

function redirect(string $path): Response
{
    return (new Response())->redirect(preg_match('#^https?://#', $path) ? $path : url($path));
}

/** Render resources/views/<nama>.php (titik = sub-folder) di dalam layout. */
function view(string $nama, array $data = []): Response
{
    $render = function (string $file, array $vars): string {
        extract($vars, EXTR_SKIP);
        ob_start();
        require $file;
        return ob_get_clean();
    };

    $file = base_path('resources/views/' . str_replace('.', '/', $nama) . '.php');
    $isi  = $render($file, $data);
    $html = $render(base_path('resources/views/layouts/app.php'), $data + ['isi' => $isi]);

    return (new Response())->html($html);
}

/* ---------- flash session: pesan sukses, error validasi, input lama ---------- */

function flash(string $kunci, $nilai = null)
{
    if ($nilai !== null) {
        $_SESSION['_flash_baru'][$kunci] = $nilai;
        return null;
    }
    return $_SESSION['_flash'][$kunci] ?? null;
}

function errors(): array
{
    return flash('errors') ?? [];
}

function old(string $kunci, string $bawaan = ''): string
{
    $lama = flash('old') ?? [];
    return isset($lama[$kunci]) && is_string($lama[$kunci]) ? $lama[$kunci] : $bawaan;
}

function csrf_token(): string
{
    if (empty($_SESSION['_token'])) {
        $_SESSION['_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function method_field(string $method): string
{
    return '<input type="hidden" name="_method" value="' . e(strtoupper($method)) . '">';
}

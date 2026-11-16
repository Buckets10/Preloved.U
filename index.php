<?php
// index.php = pintu masuk (front controller).
// 1) Request ke /programs dan /api/programs diarahkan oleh Route ke ProgramController.
// 2) Selain itu (halaman utama marketplace) tetap dilayani BarangController.
require_once __DIR__ . '/app/bootstrap.php';
require_once __DIR__ . '/Controller/BarangController.php';

Route::muat(__DIR__ . '/routes/web.php');
Route::muat(__DIR__ . '/routes/api.php', true);

if (Route::dispatch(request())) {
    exit;
}

(new BarangController())->index();

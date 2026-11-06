<?php
// index.php = pintu masuk (front controller): hanya memanggil Controller.
require_once __DIR__ . '/Controller/BarangController.php';

(new BarangController())->index();
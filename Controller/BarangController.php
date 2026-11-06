<?php
/**
 * Controller/BarangController.php
 * CONTROLLER (MVC): terima request -> minta data ke Model -> kirim ke View.
 * REQUEST -> CONTROLLER -> MODEL -> CONTROLLER -> VIEW
 */

require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../Model/Barang.php';

class BarangController
{
    public function index(): void
    {
        // 1) REQUEST: ambil input dari URL (?q=...&kategori=...)
        $q             = $this->ambilGet('q');
        $kategoriAktif = $this->kategoriValid($this->ambilGet('kategori', 'semua'));

        // 2) MODEL: minta data & saring
        $barang = Barang::semua();
        $galat  = '';

        if ($barang === null) {
            $galat  = 'File barang.json tidak ditemukan atau isinya rusak.';
            $barang = [];
        }

        $hasil = Barang::saring($barang, $q, $kategoriAktif);

        // 3) VIEW: kirim data ke tampilan
        $this->render('barang-page', [
            'namaSitus'     => 'Preloved.U',
            'tahun'         => date('Y'),
            'q'             => $q,
            'kategoriAktif' => $kategoriAktif,
            'modeFavorit'   => ($kategoriAktif === 'favorit'),
            'barang'        => $barang,
            'galat'         => $galat,
            'hasil'         => $hasil,
            'jumlah'        => count($hasil),
        ]);
    }

    private function render(string $view, array $data): void
    {
        extract($data);
        require __DIR__ . '/../View/' . $view . '.php';
    }

    // $_GET bisa berisi array (?q[]=a). Hanya teks biasa yang diterima.
    private function ambilGet(string $kunci, string $bawaan = ''): string
    {
        $nilai = $_GET[$kunci] ?? $bawaan;
        return is_string($nilai) ? trim($nilai) : $bawaan;
    }

    private function kategoriValid(string $kode): string
    {
        return array_key_exists($kode, Barang::KATEGORI) ? $kode : 'semua';
    }
}
<?php
/**
 * Model/Barang.php
 * MODEL (MVC): data barang + akses data (barang.json) + aturan bisnis.
 * Tidak ada HTML di sini, dan tidak tahu apa-apa soal $_GET / tampilan.
 */

class Barang implements JsonSerializable
{
    // ===== DATA PENDUKUNG (kode => label) =====
    public const FILE = __DIR__ . '/../barang.json';

    public const STATUS = [
        'tersedia' => 'Tersedia',
        'nego'     => 'Nego',
        'terjual'  => 'Terjual',
    ];

    public const KATEGORI = [
        'semua'      => ['label' => 'Semua',       'emoji' => ''],
        'buku'       => ['label' => 'Buku',        'emoji' => '📚'],
        'elektronik' => ['label' => 'Elektronik',  'emoji' => '🔌'],
        'perabot'    => ['label' => 'Perabot Kos', 'emoji' => '🪑'],
        'favorit'    => ['label' => 'Favorit',     'emoji' => '♥'],
    ];

    // ===== PROPERTIES =====
    private int $id;
    private string $nama;
    private float $harga;
    private string $kategori;
    private string $status;
    private string $wa;
    private string $merk;
    private string $ukuran;
    private string $kondisi;
    private string $deskripsi;
    private string $foto;

    public function __construct(array $data = [])
    {
        $this->id        = (int) ($data['id'] ?? 0);
        $this->nama      = (string) ($data['nama'] ?? '');
        $this->harga     = (float) ($data['harga'] ?? 0);
        $this->kategori  = (string) ($data['kategori'] ?? 'buku');
        $this->status    = (string) ($data['status'] ?? 'tersedia');
        $this->wa        = (string) ($data['wa'] ?? '');
        $this->merk      = (string) ($data['merk'] ?? '-');
        $this->ukuran    = (string) ($data['ukuran'] ?? '-');
        $this->kondisi   = (string) ($data['kondisi'] ?? '-');
        $this->deskripsi = (string) ($data['deskripsi'] ?? '');
        $this->foto      = (string) ($data['foto'] ?? '');
    }

    // ===== GETTERS =====
    public function getId(): int          { return $this->id; }
    public function getNama(): string     { return $this->nama; }
    public function getHarga(): float     { return $this->harga; }
    public function getKategori(): string { return $this->kategori; }
    public function getStatus(): string   { return $this->status; }
    public function getWa(): string       { return $this->wa; }
    public function getMerk(): string     { return $this->merk; }
    public function getUkuran(): string   { return $this->ukuran; }
    public function getKondisi(): string  { return $this->kondisi; }
    public function getDeskripsi(): string { return $this->deskripsi; }
    public function getFoto(): string     { return $this->foto; }

    // ===== BEHAVIOR / ATURAN BISNIS =====
    public function hasFoto(): bool
    {
        return trim($this->foto) !== '';
    }

    public function isTerjual(): bool
    {
        return strtolower($this->status) === 'terjual';
    }

    public function getLabelStatus(): string
    {
        return self::STATUS[$this->status] ?? ucfirst($this->status);
    }

    public function getLabelKategori(): string
    {
        return self::KATEGORI[$this->kategori]['label'] ?? ucfirst($this->kategori);
    }

    public function getEmojiKategori(): string
    {
        return self::KATEGORI[$this->kategori]['emoji'] ?? '📦';
    }

    public function getLinkWa(string $customMessage = ''): string
    {
        if ($customMessage === '') {
            $customMessage = "Halo, saya tertarik dengan \"{$this->nama}\" di Preloved.U. Apakah masih tersedia?";
        }
        return 'https://wa.me/' . $this->wa . '?text=' . rawurlencode($customMessage);
    }

    public function cocok(string $q, string $kategori): bool
    {
        $teks = $this->nama . ' ' . $this->deskripsi . ' ' . $this->getLabelKategori();

        $cocokKata     = ($q === '') || (stripos($teks, $q) !== false);
        $cocokKategori = in_array($kategori, ['semua', 'favorit'], true)
            || ($this->kategori === $kategori);

        return $cocokKata && $cocokKategori;
    }

    // ===== DATA ACCESS =====
    /** Baca barang.json -> array of Barang, atau null kalau file hilang / rusak. */
    public static function semua(): ?array
    {
        if (!is_readable(self::FILE)) {
            return null;
        }

        $data = json_decode((string) file_get_contents(self::FILE), true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            return null;
        }

        $daftar = [];
        foreach ($data as $item) {
            if (is_array($item)) {
                $daftar[] = new Barang($item);
            }
        }
        return $daftar;
    }

    /** Saring daftar barang berdasarkan kata kunci + kategori. */
    public static function saring(array $daftar, string $q, string $kategori): array
    {
        $hasil = [];
        foreach ($daftar as $item) {
            if ($item instanceof Barang && $item->cocok($q, $kategori)) {
                $hasil[] = $item;
            }
        }
        return $hasil;
    }

    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'nama'      => $this->nama,
            'harga'     => $this->harga,
            'kategori'  => $this->kategori,
            'status'    => $this->status,
            'wa'        => $this->wa,
            'merk'      => $this->merk,
            'ukuran'    => $this->ukuran,
            'kondisi'   => $this->kondisi,
            'deskripsi' => $this->deskripsi,
            'foto'      => $this->foto,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
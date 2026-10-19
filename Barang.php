<?php
/**
 * Barang.php
 * Representasi Objek Barang (Product) untuk marketplace Preloved.U
 * 
 * Pertemuan 6 - Web Programming:
 * - Task 01: Create a Class (CLASS -> OBJECT)
 * - Task 02: Add Properties & Methods (OBJECT = DATA + BEHAVIOR)
 * - Task 03: Generate Dynamic Content with Object (OBJECT -> PHP -> HTML)
 */

class Barang implements JsonSerializable
{
    // ============================================================
    // TASK 01: PROPERTIES (DATA)
    // ============================================================
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

    /**
     * Constructor untuk inisialisasi object Barang dari array asosiatif (misal data JSON).
     * 
     * @param array $data Data barang mentah
     */
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

    // ============================================================
    // TASK 02: GETTERS (AKSES DATA)
    // ============================================================

    public function getId(): int
    {
        return $this->id;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getHarga(): float
    {
        return $this->harga;
    }

    public function getKategori(): string
    {
        return $this->kategori;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getWa(): string
    {
        return $this->wa;
    }

    public function getMerk(): string
    {
        return $this->merk;
    }

    public function getUkuran(): string
    {
        return $this->ukuran;
    }

    public function getKondisi(): string
    {
        return $this->kondisi;
    }

    public function getDeskripsi(): string
    {
        return $this->deskripsi;
    }

    public function getFoto(): string
    {
        return $this->foto;
    }

    // ============================================================
    // TASK 02: BEHAVIOR & METHODS
    // ============================================================

    /**
     * Cek apakah barang memiliki foto yang valid
     */
    public function hasFoto(): bool
    {
        return trim($this->foto) !== '';
    }

    /**
     * Cek apakah barang sudah berstatus terjual
     */
    public function isTerjual(): bool
    {
        return strtolower($this->status) === 'terjual';
    }

    /**
     * Format harga numerik ke format Rupiah (misal: "Rp 50.000")
     */
    public function getHargaFormat(): string
    {
        return formatRupiah($this->harga);
    }

    /**
     * Ambil label status yang ramah pembaca (Tersedia, Nego, Terjual)
     */
    public function getLabelStatus(): string
    {
        return NAMA_STATUS[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Ambil label kategori yang ramah pembaca (Buku, Elektronik, Perabot Kos)
     */
    public function getLabelKategori(): string
    {
        return KATEGORI[$this->kategori]['label'] ?? ucfirst($this->kategori);
    }

    /**
     * Ambil emoji representasi kategori (📚, 🔌, 🪑)
     */
    public function getEmojiKategori(): string
    {
        return KATEGORI[$this->kategori]['emoji'] ?? '📦';
    }

    /**
     * Buat URL pesan langsung ke WhatsApp penjual
     */
    public function getLinkWa(string $customMessage = ''): string
    {
        if ($customMessage === '') {
            $customMessage = "Halo, saya tertarik dengan \"{$this->nama}\" di Preloved.U. Apakah masih tersedia?";
        }
        return 'https://wa.me/' . $this->wa . '?text=' . rawurlencode($customMessage);
    }

    /**
     * Logika pencarian & filter kategori terenkapsulasi di dalam object
     */
    public function cocok(string $q, string $kategori): bool
    {
        $labelKategori = $this->getLabelKategori();
        $teks = $this->nama . ' ' . $this->deskripsi . ' ' . $labelKategori;

        $cocokKata = ($q === '') || (stripos($teks, $q) !== false);

        $cocokKategori = in_array($kategori, ['semua', 'favorit'], true)
            || ($this->kategori === $kategori);

        return $cocokKata && $cocokKategori;
    }

    // ============================================================
    // TASK 03: GENERATE DYNAMIC CONTENT WITH OBJECT
    // (OBJECT -> METHOD -> PHP -> GENERATED HTML)
    // ============================================================

    /**
     * Menghasilkan markup HTML kartu barang secara dinamis dari object
     */
    public function renderKartu(bool $sembunyi = false): void
    {
        $id          = $this->getId();
        $nama        = $this->getNama();
        $kategori    = $this->getKategori();
        $status      = $this->getStatus();
        $foto        = $this->getFoto();
        $emoji       = $this->getEmojiKategori();
        $labelStatus = $this->getLabelStatus();
        $terjual     = $this->isTerjual();
        $hargaFormat = $this->getHargaFormat();
        ?>
        <article class="product-card<?= $terjual ? ' product-card--terjual' : '' ?>"
                 id="barang-<?= $id ?>" data-id="<?= $id ?>" data-kategori="<?= e($kategori) ?>"<?= $sembunyi ? ' hidden' : '' ?>>

          <?php if ($this->hasFoto()): ?>
            <img src="<?= e($foto) ?>" alt="<?= e($nama) ?>" loading="lazy">
          <?php else: ?>
            <div class="img-placeholder"><?= $emoji ?></div>
          <?php endif; ?>

          <button type="button" class="btn-wish" aria-label="Tambah ke favorit">♡</button>

          <h2><?= e($nama) ?></h2>
          <p class="price"><?= e($hargaFormat) ?></p>
          <span class="status status--<?= e($status) ?>"><?= e($labelStatus) ?></span>
          <button type="button" class="btn-detail">Lihat Detail</button>
        </article>
        <?php
    }

    /**
     * Konversi data object kembali menjadi array asosiatif
     */
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

    /**
     * Implementasi JsonSerializable agar json_encode($barangObject)
     * tetap kompatibel penuh dengan pembacaan oleh script.js
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}

<?php
namespace App\Http;

/**
 * Request: membungkus data yang dikirim pengguna (form / JSON / query string).
 * Alur: INPUT -> REQUEST -> VALIDATION -> VALID -> DATABASE
 */
class Request
{
    private array $input;
    private string $method;
    private string $path;

    public function __construct(?string $method = null, ?string $path = null, ?array $input = null)
    {
        $this->path   = $path ?? self::pathSaatIni();
        $this->input  = $input ?? $this->bacaInput();
        $this->method = strtoupper($method ?? $this->tentukanMethod());
    }

    public static function capture(): self
    {
        return new self();
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function all(): array
    {
        return $this->input;
    }

    public function input(string $kunci, $bawaan = null)
    {
        return $this->input[$kunci] ?? $bawaan;
    }

    /** Memungkinkan $request->nama dan $request->deskripsi seperti di Laravel. */
    public function __get(string $kunci)
    {
        return $this->input[$kunci] ?? null;
    }

    public function __isset(string $kunci): bool
    {
        return isset($this->input[$kunci]);
    }

    public function only(array $kunci): array
    {
        return array_intersect_key($this->input, array_flip($kunci));
    }

    public function isApi(): bool
    {
        return $this->path === 'api' || strncmp($this->path, 'api/', 4) === 0;
    }

    public function expectsJson(): bool
    {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        return $this->isApi()
            || stripos($accept, 'application/json') !== false
            || strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
    }

    /**
     * Validasi input. Jika gagal, melempar ValidationException
     * (-> 422 untuk JSON, atau kembali ke form untuk halaman web).
     * Jika lolos, mengembalikan data yang sudah tervalidasi.
     */
    public function validate(array $rules, array $messages = []): array
    {
        return \App\Support\Validator::make($this->input, $rules, $messages)->validate();
    }

    private function tentukanMethod(): string
    {
        $asli = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if ($asli === 'POST' && isset($this->input['_method'])) {
            $spoof = strtoupper((string) $this->input['_method']);
            if (in_array($spoof, ['PUT', 'PATCH', 'DELETE'], true)) {
                return $spoof;
            }
        }
        return $asli;
    }

    private function bacaInput(): array
    {
        $data = $_GET;
        $mentah = (string) file_get_contents('php://input');
        $tipe = strtolower($_SERVER['CONTENT_TYPE'] ?? '');

        if (strpos($tipe, 'application/json') !== false) {
            $json = json_decode($mentah, true);
            if (is_array($json)) {
                $data = array_merge($data, $json);
            }
        } elseif (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $data = array_merge($data, $_POST);
        } elseif ($mentah !== '') {
            // PUT / PATCH dengan form-urlencoded: PHP tidak mengisi $_POST, jadi diurai manual.
            parse_str($mentah, $form);
            $data = array_merge($data, $form);
        }

        return $data;
    }

    /** Path request relatif terhadap folder project, tanpa slash di awal/akhir. */
    private static function pathSaatIni(): string
    {
        $uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $uri  = rawurldecode($uri);
        $base = base_url();

        if ($base !== '' && strncmp($uri, $base, strlen($base)) === 0) {
            $uri = substr($uri, strlen($base));
        }
        $uri = trim($uri, '/');

        // index.php dianggap sama dengan "/"
        return ($uri === 'index.php') ? '' : preg_replace('#^index\.php/#', '', $uri);
    }
}

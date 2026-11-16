<?php
/**
 * app/Support/Route.php
 * Router: Route::get/post/put/patch/delete, Route::resource, Route::apiResource.
 * Alur: HTTP Request -> Route -> Controller -> Model -> Database -> Response
 */

use App\Http\Request;
use App\Http\Response;
use App\Http\ValidationException;
use App\Models\ModelNotFoundException;

class Route
{
    /** @var array[] daftar route: ['method','pattern','regex','action','api'] */
    private static array $routes = [];
    private static bool $modeApi = false;

    public static function get(string $uri, $action): void    { self::add(['GET', 'HEAD'], $uri, $action); }
    public static function post(string $uri, $action): void   { self::add(['POST'], $uri, $action); }
    public static function put(string $uri, $action): void    { self::add(['PUT'], $uri, $action); }
    public static function patch(string $uri, $action): void  { self::add(['PATCH'], $uri, $action); }
    public static function delete(string $uri, $action): void { self::add(['DELETE'], $uri, $action); }

    /**
     * Route::resource('programs', ProgramController::class)
     * index, create, store, show, edit, update, destroy (halaman web).
     */
    public static function resource(string $nama, string $controller, array $opsi = []): void
    {
        $semua = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];
        self::daftarkanResource($nama, $controller, $semua, $opsi);
    }

    /**
     * Route::apiResource('programs', ProgramController::class)
     * index, store, show, update (PUT/PATCH), destroy (tanpa create & edit karena tidak ada form).
     */
    public static function apiResource(string $nama, string $controller, array $opsi = []): void
    {
        $semua = ['index', 'store', 'show', 'update', 'destroy'];
        self::daftarkanResource($nama, $controller, $semua, $opsi);
    }

    /** Memuat file route. routes/api.php otomatis diberi prefix /api seperti di Laravel. */
    public static function muat(string $file, bool $api = false): void
    {
        self::$modeApi = $api;
        require $file;
        self::$modeApi = false;
    }

    /**
     * Mencari route yang cocok lalu menjalankannya.
     * @return bool false jika tidak ada route yang cocok (dan bukan permintaan /api)
     */
    public static function dispatch(Request $request): bool
    {
        $path = $request->path();
        $metodeCocokTapiBeda = false;

        try {
            foreach (self::$routes as $r) {
                if (!preg_match($r['regex'], $path, $m)) {
                    continue;
                }
                if (!in_array($request->method(), $r['method'], true)) {
                    $metodeCocokTapiBeda = true;
                    continue;
                }

                $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
                self::kirim(self::jalankan($r, $request, $params));
                return true;
            }
        } catch (ValidationException $e) {
            self::kirim(self::tanganiValidasi($e, $request));
            return true;
        } catch (ModelNotFoundException $e) {
            self::kirim(self::galat($request, 404, $e->getMessage(), 'Data tidak ditemukan.'));
            return true;
        } catch (\Throwable $e) {
            $debug = filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOLEAN);
            self::kirim(self::galat($request, 500, $debug ? $e->getMessage() : 'Server Error', 'Terjadi kesalahan pada server.'));
            return true;
        }

        if ($metodeCocokTapiBeda) {
            self::kirim(self::galat($request, 405, 'Method tidak diizinkan untuk URL ini.', 'Method tidak diizinkan.'));
            return true;
        }

        // Permintaan /api/... yang tidak ada route-nya harus dijawab JSON 404, bukan halaman Barang.
        if ($request->isApi()) {
            self::kirim(self::galat($request, 404, 'Endpoint tidak ditemukan.', 'Halaman tidak ditemukan.'));
            return true;
        }

        return false;
    }

    /* ------------------------------------------------------------------ */

    private static function daftarkanResource(string $nama, string $controller, array $semua, array $opsi): void
    {
        $aktif = $semua;
        if (isset($opsi['only']))   { $aktif = array_values(array_intersect($semua, $opsi['only'])); }
        if (isset($opsi['except'])) { $aktif = array_values(array_diff($aktif, $opsi['except'])); }

        $peta = [
            'index'   => [['GET', 'HEAD'], "$nama"],
            'create'  => [['GET', 'HEAD'], "$nama/create"],
            'store'   => [['POST'],        "$nama"],
            'show'    => [['GET', 'HEAD'], "$nama/{id}"],
            'edit'    => [['GET', 'HEAD'], "$nama/{id}/edit"],
            'update'  => [['PUT', 'PATCH'], "$nama/{id}"],
            'destroy' => [['DELETE'],      "$nama/{id}"],
        ];

        // Urutan $semua dipertahankan supaya /programs/create dicek sebelum /programs/{id}.
        foreach ($semua as $aksi) {
            if (in_array($aksi, $aktif, true)) {
                self::add($peta[$aksi][0], $peta[$aksi][1], [$controller, $aksi]);
            }
        }
    }

    private static function add(array $methods, string $uri, $action): void
    {
        $uri = trim($uri, '/');
        if (self::$modeApi) {
            $uri = 'api' . ($uri !== '' ? '/' . $uri : '');
        }

        $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $uri) . '$#';
        self::$routes[] = [
            'method'  => $methods,
            'pattern' => $uri,
            'regex'   => $regex,
            'action'  => $action,
            'api'     => self::$modeApi,
        ];
    }

    private static function jalankan(array $route, Request $request, array $params)
    {
        // Route web (bukan /api) yang mengubah data wajib membawa token CSRF.
        if (!$route['api'] && !in_array($request->method(), ['GET', 'HEAD'], true) && !$request->expectsJson()) {
            $token = (string) $request->input('_token', '');
            if ($token === '' || !hash_equals(csrf_token(), $token)) {
                return self::galat($request, 419, 'Token CSRF tidak valid. Muat ulang halaman lalu coba lagi.', 'Sesi habis. Muat ulang halaman lalu coba lagi.');
            }
        }

        [$kelas, $metode] = $route['action'];
        $controller = new $kelas();

        // Isi argumen method: parameter bertipe Request diberi $request, sisanya diisi dari URL ({id}).
        $args = [];
        $sisa = array_values($params);
        foreach ((new \ReflectionMethod($controller, $metode))->getParameters() as $p) {
            $tipe = $p->getType();
            if ($tipe instanceof \ReflectionNamedType && $tipe->getName() === Request::class) {
                $args[] = $request;
            } else {
                $args[] = array_shift($sisa);
            }
        }

        return $controller->$metode(...$args);
    }

    private static function tanganiValidasi(ValidationException $e, Request $request): Response
    {
        if ($request->expectsJson()) {
            return (new Response())->json(['message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        }

        // Halaman web: kembali ke form, tampilkan error di bawah field, dan isi ulang input lama.
        flash('errors', $e->errors());
        flash('old', $request->all());
        $asal = $_SERVER['HTTP_REFERER'] ?? url('programs');
        return (new Response())->redirect($asal);
    }

    private static function galat(Request $request, int $status, string $pesanJson, string $pesanHalaman): Response
    {
        if ($request->expectsJson()) {
            return (new Response())->json(['message' => $pesanJson], $status);
        }
        $html = '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>' . $status . '</title>'
              . '<link rel="stylesheet" href="' . e(url('style.css')) . '"></head><body>'
              . '<main style="max-width:600px;margin:80px auto;padding:0 24px;text-align:center">'
              . '<h1>' . $status . '</h1><p>' . e($pesanHalaman) . '</p>'
              . '<p style="margin-top:20px"><a href="' . e(url('programs')) . '">Kembali ke daftar program</a></p>'
              . '</main></body></html>';
        return (new Response())->html($html, $status);
    }

    private static function kirim($hasil): void
    {
        if ($hasil instanceof Response) {
            $hasil->send();
        } elseif (is_array($hasil) || $hasil instanceof \JsonSerializable) {
            (new Response())->json($hasil)->send();
        } else {
            echo (string) $hasil;
        }
    }
}

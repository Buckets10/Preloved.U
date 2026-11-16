<?php
namespace App\Support;

use App\Http\ValidationException;

/**
 * Validator sederhana dengan aturan bergaya Laravel: 'required|max:100'.
 * Aturan yang didukung: required, string, numeric, integer, min:n, max:n.
 * Pesan error diambil dari resources/lang/validation.php.
 */
class Validator
{
    private array $data;
    private array $rules;
    private array $customMessages;

    public static function make(array $data, array $rules, array $messages = []): self
    {
        $v = new self();
        $v->data = $data;
        $v->rules = $rules;
        $v->customMessages = $messages;
        return $v;
    }

    /** @return array data tervalidasi; melempar ValidationException bila ada yang gagal */
    public function validate(): array
    {
        $errors = [];
        $valid  = [];

        foreach ($this->rules as $field => $aturan) {
            $aturanList = is_array($aturan) ? $aturan : explode('|', $aturan);
            $nilai = $this->data[$field] ?? null;

            foreach ($aturanList as $aturanItem) {
                [$nama, $param] = array_pad(explode(':', (string) $aturanItem, 2), 2, null);

                if (!$this->lolos($nama, $nilai, $param)) {
                    $errors[$field][] = $this->pesan($field, $nama, $param);
                    break; // satu pesan per field sudah cukup, sama seperti bail
                }
            }

            if (!isset($errors[$field]) && array_key_exists($field, $this->data)) {
                $valid[$field] = $nilai;
            }
        }

        if ($errors) {
            throw new ValidationException($errors);
        }

        return $valid;
    }

    private function kosong($nilai): bool
    {
        return $nilai === null || (is_string($nilai) && trim($nilai) === '') || $nilai === [];
    }

    private function lolos(string $aturan, $nilai, ?string $param): bool
    {
        // Aturan selain "required" dilewati kalau field-nya memang kosong.
        if ($aturan !== 'required' && $this->kosong($nilai)) {
            return true;
        }

        switch ($aturan) {
            case 'required':
                return !$this->kosong($nilai);
            case 'string':
                return is_string($nilai);
            case 'numeric':
                return is_numeric($nilai);
            case 'integer':
                return filter_var($nilai, FILTER_VALIDATE_INT) !== false;
            case 'max':
                return $this->ukuran($nilai) <= (float) $param;
            case 'min':
                return $this->ukuran($nilai) >= (float) $param;
        }

        throw new \InvalidArgumentException("Aturan validasi [$aturan] tidak dikenal.");
    }

    private function ukuran($nilai): float
    {
        return is_numeric($nilai) && !is_string($nilai) ? (float) $nilai : (float) mb_strlen((string) $nilai);
    }

    private function pesan(string $field, string $aturan, ?string $param): string
    {
        $kunciKhusus = "$field.$aturan";
        if (isset($this->customMessages[$kunciKhusus])) {
            return $this->customMessages[$kunciKhusus];
        }

        $bahasa = require base_path('resources/lang/validation.php');
        $atribut = $bahasa['attributes'][$field] ?? ucfirst(str_replace('_', ' ', $field));
        $templat = $bahasa['messages'][$aturan] ?? ':attribute tidak valid.';

        return str_replace([':attribute', ':max', ':min'], [$atribut, (string) $param, (string) $param], $templat);
    }
}

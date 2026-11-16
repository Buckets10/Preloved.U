<?php
namespace App\Http;

class Response
{
    private int $status = 200;
    private array $headers = [];
    private string $body = '';

    public function json($data, int $status = 200): self
    {
        $this->status = $status;
        $this->headers['Content-Type'] = 'application/json; charset=utf-8';
        $this->body = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        return $this;
    }

    public function html(string $html, int $status = 200): self
    {
        $this->status = $status;
        $this->headers['Content-Type'] = 'text/html; charset=utf-8';
        $this->body = $html;
        return $this;
    }

    public function redirect(string $lokasi, int $status = 302): self
    {
        $this->status = $status;
        $this->headers['Location'] = $lokasi;
        return $this;
    }

    /** Simpan pesan flash (hanya hidup sampai request berikutnya), mis. ->with('sukses', '...') */
    public function with(string $kunci, $nilai): self
    {
        flash($kunci, $nilai);
        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getContent(): string
    {
        return $this->body;
    }

    public function send(): void
    {
        http_response_code($this->status);
        foreach ($this->headers as $nama => $nilai) {
            header("$nama: $nilai");
        }
        echo $this->body;
    }
}

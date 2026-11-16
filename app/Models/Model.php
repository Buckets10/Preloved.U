<?php
namespace App\Models;

use App\Database\Connection;

/**
 * Model dasar bergaya Eloquent (Active Record):
 *   Program::all(), Program::find($id), Program::findOrFail($id), Program::create([...]),
 *   $program->update([...]), $program->delete()
 * Berinteraksi dengan database lewat PDO prepared statement.
 */
abstract class Model implements \JsonSerializable
{
    protected static string $table = '';
    protected static array $fillable = [];

    protected array $attributes = [];

    public function __construct(array $atribut = [])
    {
        $this->attributes = $atribut;
    }

    public function __get(string $kunci)
    {
        return $this->attributes[$kunci] ?? null;
    }

    public function __set(string $kunci, $nilai): void
    {
        $this->attributes[$kunci] = $nilai;
    }

    public function toArray(): array
    {
        $data = $this->attributes;
        if (isset($data['id'])) {
            $data['id'] = (int) $data['id'];
        }
        return $data;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /** Hanya kolom di $fillable yang boleh diisi massal (mencegah mass assignment). */
    protected static function filterFillable(array $data): array
    {
        return array_intersect_key($data, array_flip(static::$fillable));
    }

    /* ---------------- READ ---------------- */

    /** @return static[] */
    public static function all(): array
    {
        $baris = Connection::get()->query('SELECT * FROM ' . static::$table . ' ORDER BY id ASC')->fetchAll();
        return array_map(fn ($b) => new static($b), $baris);
    }

    public static function find($id): ?static
    {
        $stmt = Connection::get()->prepare('SELECT * FROM ' . static::$table . ' WHERE id = ? LIMIT 1');
        $stmt->execute([(int) $id]);
        $baris = $stmt->fetch();
        return $baris ? new static($baris) : null;
    }

    public static function findOrFail($id): static
    {
        $model = static::find($id);
        if ($model === null) {
            throw new ModelNotFoundException('No query results for model [' . static::class . '] ' . $id . '.');
        }
        return $model;
    }

    /* ---------------- CREATE ---------------- */

    public static function create(array $data): static
    {
        $data = static::filterFillable($data);
        $kolom = array_keys($data);

        $sql = 'INSERT INTO ' . static::$table . ' (' . implode(', ', $kolom) . ') VALUES ('
             . implode(', ', array_fill(0, count($kolom), '?')) . ')';

        $pdo = Connection::get();
        $pdo->prepare($sql)->execute(array_values($data));

        return static::findOrFail((int) $pdo->lastInsertId());
    }

    /* ---------------- UPDATE ---------------- */

    public function update(array $data): bool
    {
        $data = static::filterFillable($data);
        if (!$data) {
            return true;
        }

        $set = implode(', ', array_map(fn ($k) => "$k = ?", array_keys($data)));
        $stmt = Connection::get()->prepare('UPDATE ' . static::$table . " SET $set WHERE id = ?");
        $ok = $stmt->execute([...array_values($data), (int) $this->id]);

        $this->attributes = array_merge($this->attributes, $data);
        return $ok;
    }

    /* ---------------- DELETE ---------------- */

    public function delete(): bool
    {
        $stmt = Connection::get()->prepare('DELETE FROM ' . static::$table . ' WHERE id = ?');
        return $stmt->execute([(int) $this->id]);
    }
}

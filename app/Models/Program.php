<?php
namespace App\Models;

/**
 * Model Program -> tabel `programs` (kolom: id, nama, deskripsi).
 * Alur: Controller -> Eloquent (Program) -> Database
 */
class Program extends Model
{
    protected static string $table = 'programs';

    protected static array $fillable = ['nama', 'deskripsi'];
}

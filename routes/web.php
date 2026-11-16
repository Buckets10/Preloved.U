<?php
/**
 * routes/web.php : halaman web CRUD Program (HTML).
 * Menghasilkan: GET /programs, /programs/create, POST /programs,
 *               GET /programs/{id}/edit, PUT/PATCH /programs/{id}, DELETE /programs/{id}
 */
use App\Http\Controllers\ProgramController;

Route::resource('programs', ProgramController::class, ['except' => ['show']]);

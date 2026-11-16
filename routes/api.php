<?php
/**
 * routes/api.php : RESTful API (JSON). Semua URL otomatis diawali /api.
 *   GET            /api/programs        -> index
 *   POST           /api/programs        -> store
 *   GET            /api/programs/{id}   -> show
 *   PUT | PATCH    /api/programs/{id}   -> update
 *   DELETE         /api/programs/{id}   -> destroy
 */
use App\Http\Controllers\ProgramController;

Route::apiResource('programs', ProgramController::class);

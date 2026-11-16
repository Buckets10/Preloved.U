<?php
namespace App\Http\Controllers;

use App\Http\Request;
use App\Models\Program;

/**
 * ProgramController: memproses request CRUD Program.
 *
 * Task 01 - Move CRUD into Controller : index, create, store, edit, update, destroy
 * Task 02 - Add Request & Validation  : validate() di store() dan update()
 * Task 03 - Build RESTful Program API : controller yang sama melayani /api/programs (JSON)
 *
 * Route -> Controller -> Eloquent (Program) -> Database
 */
class ProgramController
{
    /** Aturan validasi dipakai bersama oleh store() dan update(). */
    private const RULES = [
        'nama'      => 'required|max:100',
        'deskripsi' => 'required',
    ];

    // READ -------------------------------------------------------------------
    public function index()
    {
        $programs = Program::all();

        if (request()->expectsJson()) {            // GET /api/programs
            return response()->json(['data' => $programs]);
        }

        return view('programs.index', compact('programs'));
    }

    // FORM -------------------------------------------------------------------
    public function create()
    {
        return view('programs.create');
    }

    // CREATE -----------------------------------------------------------------
    public function store(Request $request)
    {
        // validasi
        $request->validate(self::RULES);

        // simpan ke database
        $program = Program::create([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        if ($request->expectsJson()) {             // POST /api/programs
            return response()->json(['message' => 'Berhasil', 'data' => $program], 200);
        }

        return redirect('programs')->with('sukses', 'Program berhasil ditambahkan.');
    }

    // DETAIL (dipakai API: GET /api/programs/{id}) -------------------------------
    public function show($id)
    {
        $program = Program::findOrFail($id);

        return response()->json(['data' => $program]);
    }

    // FORM EDIT --------------------------------------------------------------
    public function edit($id)
    {
        $program = Program::findOrFail($id);

        return view('programs.edit', compact('program'));
    }

    // UPDATE -----------------------------------------------------------------
    public function update(Request $request, $id)
    {
        // validasi
        $request->validate(self::RULES);

        // update data
        $program = Program::findOrFail($id);
        $program->update([
            'nama'      => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        if ($request->expectsJson()) {             // PUT/PATCH /api/programs/{id}
            return response()->json(['message' => 'Berhasil', 'data' => $program], 200);
        }

        return redirect('programs')->with('sukses', 'Program berhasil diperbarui.');
    }

    // DELETE -----------------------------------------------------------------
    public function destroy($id)
    {
        Program::findOrFail($id)->delete();

        if (request()->expectsJson()) {            // DELETE /api/programs/{id}
            return response()->json(['message' => 'Berhasil dihapus']);
        }

        return redirect('programs')->with('sukses', 'Program berhasil dihapus.');
    }
}

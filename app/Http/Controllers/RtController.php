<?php

// app/Http/Controllers/RtController.php

namespace App\Http\Controllers;

use App\Models\Rt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RtController extends Controller
{
    // Menampilkan semua RT
    public function index()
    {
        $rts = Rt::all();
        return response()->json($rts);
    }

    // Menampilkan RT berdasarkan ID
    public function show($id)
    {
        $rt = Rt::find($id);

        if (!$rt) {
            return response()->json(['message' => 'RT not found'], 404);
        }

        return response()->json($rt);
    }

    // Menambahkan RT baru
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'rw' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        // Membuat RT baru
        $rt = Rt::create([
            'name' => $request->name,
            'rw' => $request->rw,
            'alamat' => $request->alamat,
        ]);

        return response()->json(['message' => 'RT created successfully', 'rt' => $rt], 201);
    }

    // Memperbarui RT berdasarkan ID
    public function update(Request $request, $id)
    {
        $rt = Rt::find($id);

        if (!$rt) {
            return response()->json(['message' => 'RT not found'], 404);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'rw' => 'sometimes|string|max:255',
            'alamat' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        // Memperbarui data RT
        $rt->update([
            'name' => $request->name ?? $rt->name,
            'rw' => $request->rw ?? $rt->rw,
            'alamat' => $request->alamat ?? $rt->alamat,
        ]);

        return response()->json(['message' => 'RT updated successfully', 'rt' => $rt]);
    }

    // Menghapus RT berdasarkan ID
    public function destroy($id)
    {
        $rt = Rt::find($id);

        if (!$rt) {
            return response()->json(['message' => 'RT not found'], 404);
        }

        $rt->delete();

        return response()->json(['message' => 'RT deleted successfully']);
    }
}
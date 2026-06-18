<?php

// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // Menampilkan semua kategori
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    // Menampilkan kategori berdasarkan ID
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category);
    }

    // Membuat kategori baru
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price_per_kg' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        // Membuat kategori baru
        $category = Category::create([
            'name' => $request->name,
            'price_per_kg' => $request->price_per_kg,
            'unit' => $request->unit,
        ]);

        return response()->json(['message' => 'Category created successfully', 'data' => $category], 201);
    }

    // Memperbarui kategori
    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'price_per_kg' => 'sometimes|numeric|min:0',
            'unit' => 'sometimes|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Update kategori
        $category->update([
            'name' => $request->name ?? $category->name,
            'price_per_kg' => $request->price_per_kg ?? $category->price_per_kg,
            'unit' => $request->unit ?? $category->unit,
        ]);

        return response()->json(['message' => 'Category updated successfully', 'category' => $category]);
    }

    // Menghapus kategori
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}

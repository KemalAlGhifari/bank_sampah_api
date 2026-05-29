<?php

// app/Http/Controllers/DepositController.php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Deposit;
use App\Models\DepositItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepositController extends Controller
{
    // Mengestimasi harga berdasarkan category_id dan weight
    public function estimatePrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'weight' => 'required|numeric|min:0.1',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 400);
        }

        $category = Category::find($request->category_id);
        $estimatedPrice = $category->price_per_kg * $request->weight;

        return response()->json([
            'success' => true,
            'category_id' => $request->category_id,
            'category_name' => $category->name,
            'weight' => $request->weight,
            'price_per_kg' => $category->price_per_kg,
            'estimated_price' => $estimatedPrice,
        ]);
    }

    // Menampilkan semua setoran
    public function index(Request $request)
    {
        // Ambil page size dari query parameter, default 10
        $pageSize = $request->query('page_size', 10);
        
        // Ambil order by dari query parameter, default 'created_at'
        $orderBy = $request->query('order_by', 'created_at');
        
        // Ambil order direction dari query parameter, default 'desc'
        $orderDirection = $request->query('order', 'desc');

        // Ambil data setoran dengan relasi depositItems dan category, paginasi, dan sorting
        $deposits = Deposit::with('depositItems.category')
            ->with('user')
            ->orderBy($orderBy, $orderDirection)
            ->paginate($pageSize);

        return response()->json($deposits);
    }

    // Menampilkan setoran berdasarkan ID
    public function show($id)
    {
        $deposit = Deposit::with('depositItems.category')->find($id);

        if (! $deposit) {
            return response()->json(['message' => 'Deposit not found'], 404);
        }

        return response()->json($deposit);
    }

    // Membuat setoran baru
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id', // Pastikan user ada
            'deposit_items' => 'required|array', // Setoran harus berisi item
            'deposit_items.*.category_id' => 'required|exists:categories,id', // Setiap item harus punya kategori yang valid
            'deposit_items.*.weight' => 'required|numeric|min:0', // Berat sampah harus lebih dari 0
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        $user = User::find($request->user_id);

        // Membuat setoran
        $deposit = Deposit::create([
            'user_id' => $request->user_id,
            'total_kg' => array_sum(array_column($request->deposit_items, 'weight')),
            'total_amount' => 0, // Akan dihitung setelah item disimpan
            'status' => 'approved', // Status default
        ]);

        // Menambahkan item setoran
        $totalAmount = 0;
        foreach ($request->deposit_items as $item) {
            $category = Category::find($item['category_id']);
            $subtotal = $category->price_per_kg * $item['weight'];
            $totalAmount += $subtotal;

            DepositItem::create([
                'deposit_id' => $deposit->id,
                'category_id' => $item['category_id'],
                'weight' => $item['weight'],
                'subtotal' => $subtotal,
            ]);
        }
        
         // Update total kg pada user
         $user->increment('total_kg', $deposit->total_kg);

         // Update saldo pada user
         $user->increment('saldo', $totalAmount);

        // Update total amount pada setoran
        $deposit->update(['total_amount' => $totalAmount]);

        return response()->json(['message' => 'Deposit created successfully', 'deposit' => $deposit]);
    }

    // Memperbarui setoran
    public function update(Request $request, $id)
    {
        $deposit = Deposit::find($id);

        if (! $deposit) {
            return response()->json(['message' => 'Deposit not found'], 404);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'deposit_items' => 'required|array',
            'deposit_items.*.category_id' => 'required|exists:categories,id',
            'deposit_items.*.weight' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        // Menghapus item lama dan membuat item baru
        $deposit->depositItems()->delete();
        $totalAmount = 0;
        foreach ($request->deposit_items as $item) {
            $category = Category::find($item['category_id']);
            $subtotal = $category->price_per_kg * $item['weight'];
            $totalAmount += $subtotal;

            DepositItem::create([
                'deposit_id' => $deposit->id,
                'category_id' => $item['category_id'],
                'weight' => $item['weight'],
                'subtotal' => $subtotal,
            ]);
        }

        // Update total amount pada setoran
        $deposit->update(['total_amount' => $totalAmount]);

        return response()->json(['message' => 'Deposit updated successfully', 'deposit' => $deposit]);
    }

    // Menghapus setoran
    public function destroy($id)
    {
        $deposit = Deposit::find($id);

        if (! $deposit) {
            return response()->json(['message' => 'Deposit not found'], 404);
        }

        // Hapus item setoran terlebih dahulu
        $deposit->depositItems()->delete();
        $deposit->delete();

        return response()->json(['message' => 'Deposit deleted successfully']);
    }
}

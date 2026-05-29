<?php

// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Menampilkan daftar semua pengguna
    public function index()
    {
        $users = User::with('rt')
            ->withSum('deposits as total_setoran', 'total_amount')
            ->withSum('deposits as total_deposit_kg', 'total_kg')
            ->get()
            ->map(function ($user) {
                $user->rt_name = $user->rt->name ?? 'Unknown';
                $user->rw_name = $user->rt->rw ?? 'Unknown';

                // Cast sums to floats/ints for JSON clarity
                $user->total_setoran = (float) ($user->total_setoran ?? 0);

                // Prefer computed sum of deposits for total kg, fallback to stored column
                $user->total_kg = (float) ($user->total_deposit_kg ?? $user->total_kg ?? 0);

                // remove intermediate field if present
                if (isset($user->total_deposit_kg)) {
                    unset($user->total_deposit_kg);
                }

                return $user;
            });

        return response()->json($users);
    }

    // Menampilkan data pengguna berdasarkan ID
    public function show($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found1'], 404);
        }

        return response()->json($user);
    }

    // Menampilkan daftar pengguna dengan role 'user' menggunakan pagination
    public function getUsers(Request $request)
    {
        $perPage = $request->input('per_page', 100);

        $users = User::where('role', 'user')
            ->with('rt')
            ->paginate($perPage);

        return response()->json($users);
    }

    // Membuat pengguna baru
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nik' => 'required|digits:16', // NIK 16 digit
            'alamat' => 'required|string',
            'phone' => 'required|unique:users|regex:/^08[1-9][0-9]{6,9}$/', // format nomor HP
            'password' => 'required|string|min:6',
            'role' => 'required|in:user,admin',
            'rt_id' => 'required|exists:rts,id', // Pastikan RT ada
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 400);
        }

        // Buat pengguna baru
        $user = User::create([
            'name' => $request->name,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), // Hash password
            'role' => $request->role,
            'rt_id' => $request->rt_id,
            'total_kg' => 0,  // Default
            'saldo' => 0,     // Default
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    // Memperbarui data pengguna
    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'nik' => 'sometimes|digits:16',
            'alamat' => 'sometimes|string',
            'phone' => [
                'sometimes',
                'regex:/^08[1-9][0-9]{6,9}$/', // format nomor HP
                Rule::unique('users')->ignore($id),
            ],
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|in:user,admin',
            'rt_id' => 'sometimes|exists:rts,id', // Pastikan RT ada
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 400);
        }

        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Update data pengguna
        $user->update([
            'name' => $request->name ?? $user->name,
            'nik' => $request->nik ?? $user->nik,
            'alamat' => $request->alamat ?? $user->alamat,
            'phone' => $request->phone ?? $user->phone,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'role' => $request->role ?? $user->role,
            'rt_id' => $request->rt_id ?? $user->rt_id,
        ]);

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    // Menghapus pengguna
    public function destroy($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}

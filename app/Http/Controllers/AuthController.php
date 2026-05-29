<?php

// app/Http/Controllers/AuthController.ph
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|exists:users',
            'password' => 'required',
            'fcm_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 400);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }

        if ($request->fcm_token) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }

        $token = $user->createToken('bank-sampah-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->fcm_token = null;
        $request->user()->save();

        $request->user()->currentAccessToken()->delete();

        

        return response()->json(['message' => 'Logged out successfully']);
    }
}

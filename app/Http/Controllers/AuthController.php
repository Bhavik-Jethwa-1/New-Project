<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\IsAdmin;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|unique:users,mobile_number|min:10|max:10',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'full_name' => $request->full_name,
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role
        ]);

        return response()->json(['message' => 'Registration successful', 'user' => $user], 201);
    }

    // User Login
    public function login(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt(['mobile_number' => $request->mobile_number, 'password' => $request->password])) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['message' => 'Login successful', 'token' => $token, 'user' => $user], 200);
    }

    // User Logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout successful'], 200);
    }
}

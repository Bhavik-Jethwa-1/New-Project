<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'mobile_number' => 'required|string|size:10|unique:users,mobile_number',
                'password' => 'required|string|confirmed|min:8',
            ]);

            $user = User::create([
                'full_name' => $validated['full_name'],
                'mobile_number' => $validated['mobile_number'],
                'password' => Hash::make($validated['password']),
                'role' => 'user', // default role
            ]);

            return response()->json([
                'message' => 'Registration successful',
                'user' => $user
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Registration Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong during registration.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * User login and token creation
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'mobile_number' => 'required|string|size:10',
                'password' => 'required|string',
            ]);

            if (!Auth::attempt([
                'mobile_number' => $validated['mobile_number'],
                'password' => $validated['password']
            ])) {
                return response()->json([
                    'message' => 'Invalid credentials'
                ], 401);
            }

            $user = Auth::user();
            $user->is_admin = $user->role === 'admin' ? true : false;
            $user->save();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'mobile_number' => $user->mobile_number,
                    'role' => $user->role,
                    'is_admin' => (bool) $user->is_admin,
                ]
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Login Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong during login.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout and revoke tokens
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logout successful'
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Logout Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong during logout.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\VolunteerRequest;
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
                'mobile_number' => 'required|digits:10|unique:users,mobile_number',
                'password' => 'required|string|confirmed|min:8',
            ]);
    
            // Create the user
            $user = User::create([
                'full_name' => $validated['full_name'],
                'mobile_number' => $validated['mobile_number'],
                'password' => Hash::make($validated['password']),
                'role' => 'user', // default role
            ]);
    
            // Automatically create a volunteer request
            VolunteerRequest::create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
    
            return response()->json([
                'message' => 'Registration successful and volunteer request submitted.',
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
            'mobile_number' => 'required|digits:10',
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
        
        // Update authenticated status only if is_admin is null
        if ($user->is_admin === null) {
            $user->is_admin = $user->role === 'admin' ? true : false;
        }
        
        $user->authenticated = true;
        $user->save();

        $token = $user->createToken('AppToken')->accessToken;

        return response()->json([
            'message' => 'Login successful',
            'authenticated' => $user->authenticated,
            'is_admin' => (bool) $user->is_admin,
            'role' => $user->role,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'mobile_number' => $user->mobile_number,
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
            $user = Auth::user();
        
        // Update authenticated status only if is_admin is null
            if ($user->is_admin === null) {
                $user->authenticated = null;
                $user->save();
            }

            $request->user()->token()->revoke();

            return response()->json([
                'message' => 'Successfully logged out'
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

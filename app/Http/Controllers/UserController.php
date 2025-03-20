<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'mobile_number' => 'required|string|unique:users,mobile_number',
        'password' => 'required|string|confirmed|min:8',
    ]);

    $validated['password'] = Hash::make($validated['password']);
    $validated['role'] = 'user';

    $user = User::create($validated);

    return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
}
}

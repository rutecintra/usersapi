<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $validated['password'] = Hash::make($validated['password']);
            
            $user = User::create($validated);

            return response()->json([
                'access_token' => $user->createToken('auth_token')->plainTextToken,
                'token_type' => 'Bearer'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function login(Request $request)
    {
        try {

            if ( ! Auth::attempt($request->only('email', 'password'))) {

                return response()->json(['message' => 'Invalid credentials'], 401);
            }
    
            return response()->json([
                'access_token' => $request->user()->createToken('auth_token')->plainTextToken,
                'token_type' => 'Bearer'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'message' => 'Login failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        try {

            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Sucessfull logout']);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'message' => 'Logout failed',
                'errors' => $e->errors()
            ], 422);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        try {

            $users = User::all();
            return response()->json($users);
            
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'message' => 'Users not found'
            ], 404);
            
        } catch (\Illuminate\Database\QueryException $e) {

            \Log::error('Database error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Database connection error'
            ], 500);
            
        } catch (\Exception $e) {

            \Log::error('Unexpected error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users|max:255',
                'password' => 'required|string|min:8',
            ]);

            $validated['password'] = Hash::make($validated['password']);
            
            $user = User::create($validated);
            return response()->json($user, 201);

        } catch (\Exception $e) {

            \Log::error('Create error: ' . $e->getMessage());
            return response()->json(['message' => 'Creation failed'], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        try {

            $user = User::findOrFail($id);
            return response()->json($user);

        } catch (ModelNotFoundException $e) {

            return response()->json(['message' => 'User not found'], 404);

        } catch (\Exception $e) {

            \Log::error('Show error: ' . $e->getMessage());
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {

            $user = User::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:users,email,'.$user->id,
                'password' => 'sometimes|string|min:8',
            ]);

            if(isset($validated['password'])) {

                $validated['password'] = Hash::make($validated['password']);
            }

            $user->update($validated);
            return response()->json($user);

        } catch (ModelNotFoundException $e) {

            return response()->json(['message' => 'User not found'], 404);

        } catch (\Exception $e) {

            \Log::error('Update error: ' . $e->getMessage());
            return response()->json(['message' => 'Update failed'], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {

            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(null, 204);


        } catch (ModelNotFoundException $e) {
            
            return response()->json(['message' => 'User not found'], 404);

        } catch (\Exception $e) {

            \Log::error('Delete error: ' . $e->getMessage());
            return response()->json(['message' => 'Deletion failed'], 500);
        }
    }
}

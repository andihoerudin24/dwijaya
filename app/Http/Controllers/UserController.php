<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        // Membuat pengguna baru
        $user = User::createUser($request->only('name', 'email', 'password'));
        // Mengembalikan response dengan status 201 dan data pengguna yang baru disimpan (kecuali password)
        return ResponseService::success((array) $user, 201);
    }

    public function destroy($id)
    {
        try {
            // Find the user by ID
            $user = User::findOrFail($id);

            // Attempt to delete the user and related posts
            if ($user->deleteWithPosts()) {
                return response()->json(['message' => 'User and related posts deleted successfully'], 200);
            } else {
                return response()->json(['error' => 'Failed to delete user and related posts'], 500);
            }
        } catch (\Exception $e) {
            // Log the error message for debugging
            Log::error('Error deleting user: ' . $e->getMessage());

            return response()->json(['error' => 'Failed to delete user'], 500);
        }
    }

}

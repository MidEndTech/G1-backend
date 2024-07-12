<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;

class SignupController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            // Determine the role based on the request endpoint or other conditions
            $role = $request->is('api/register/admin') ? 'admin' : 'user';

            // Validate the incoming request data
            $validated = $request->validated();

            // Attempt to create a new user record
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $role,
                $bio = $validated['bio'] ?? null,
            ]);

            // Return a success response with the created user data
            return response()->json([
                'status' => true,
                'message' => ucfirst($role) . ' registered successfully',
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            Log::error('User registration failed: ' . $e->getMessage(), ['exception' => $e]);

            // Handle specific exceptions here
            if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] == 1062) {
                return response()->json([
                    'status' => false,
                    'message' => __('validation.email_taken'),
                ], 409);
            }

            // For any other unexpected exceptions, return a generic error response
            return response()->json([
                'status' => false,
                'message' => 'Failed to register user. Please try again later.',
            ], 500);
        }
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}

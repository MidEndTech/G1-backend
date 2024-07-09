<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
    public function register(Request $request)
    {
        // Determine the role based on the request endpoint or other conditions
        $role = $request->is('api/register/admin') ? 'admin' : 'user';

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8,'
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => __('validation.failed'),
                'errors' => $validator->errors(),
                'locale' => session('locale', App::getLocale()), // Access session locale

            ], 400);
        }

        try {
            // Attempt to create a new user record
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $role,
                'bio' => $request->bio,
            ]);

            // event(new Registered($user));
            $token = $user->createToken('API_TOKEN')->plainTextToken;
            $token = $user->createToken('API_TOKEN')->plainTextToken;


            // Return a success response with the created user data
            return response()->json([
                'status' => true,
                'message' => ucfirst($role) . ' registered successfully',
                'user' => $user,
                'token' => $token,

            ], 200);
        } catch (\Exception $e) {
            Log::error('User registration failed: ' . $e->getMessage(), ['exception' => $e]);

            // Handle specific exceptions here
            if ($e instanceof \Illuminate\Database\QueryException && $e->errorInfo[1] == 1062) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email address is already taken.',
                ], 409);
            }

            // For any other unexpecpted exceptions, return a generic error response
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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\hash;
use Illuminate\Support\Facades\App;

use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function loginUser(LogRequest $request)
    {
        try {
            // Validate request inputs
            $validateUser = $request->validated();
            // Attempt to authenticate user
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' =>  __('validation.current_password'),
                ], 401);
            }

            // Retrieve authenticated user
            $user = User::where('email', $request->email)->first();

            // Generate token with user's role as ability
            $token = $user->createToken('API_TOKEN')->plainTextToken;

            // Return successful response with token
            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully',
                'role' => $user->role, // Include the user role in the response
                'token' => $token,
            ], 200);
        } catch (\Throwable $th) {
            // Return error response in case of an exception
            return response()->json([
                'status' => false,
                'message' => __('login_failed'), // Message key
            ], 500);
        }
    }
}

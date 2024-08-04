<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LogRequest;
use App\Jobs\SendOtp;
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
            $validateUser = $request->validated();
            // Attempt to authenticate user
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => __('validation.current_password'),
                ], 401);
            }
            $user = User::where('email', $request->email)->first();

            // Create OTP and send it on queue
       $user->generateOtp();
        SendOtp::dispatch($user);
        
            return response()->json([
                'status' => true,
                'message' => 'OTP sent to your email.',
            ], 200);
        } catch (\Throwable $th) {
            // Return error response in case of an exception
            return response()->json([
                'status' => false,
                'message' => __('login_failed'),
            ], 500);
        }
    }
  
}

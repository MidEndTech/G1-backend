<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendOtp;
use Illuminate\Http\Request;
use App\Models\User;

class OtpController extends Controller
{
    public function verifyOtp(Request $request)
    {
        try {
            $userId = Auth::id();
            $user = User::find($userId);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            if ($user->isOtpValid($request->otp)) {
                $user->clearOtp();

                // Generate token with user's role as ability
                $token = $user->createToken('API_TOKEN')->plainTextToken;

                // Return successful response with token
                return response()->json([
                    'status' => true,
                    'message' => 'OTP verified successfully',
                    'role' => $user->role,
                    'token' => $token,
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired OTP.',
            ], 401);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => __('otp_verification_failed'),
            ], 500);
        }
    }

    public function resend()
    {
        $userid = Auth::user()->id;
        $user = User::findOrFail($userid);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.',
            ], 404);
        }
        $user->generateOtp();
        SendOtp::dispatch($user);
        // $verificationResponse = $this->verifyOtp($user);
    }
}

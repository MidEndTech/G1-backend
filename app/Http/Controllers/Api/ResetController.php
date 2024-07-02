<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetController extends Controller
{
    public function reset(Request $request) {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is authorized
        if (!$user) {
            return response()->json([
            'error' => 'Unauthorized'
        ], 403);
    }
        $userId = Auth::user()->id;
        $user = User::find($userId);
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
            'error' => 'Validation failed',
        ], 422); // 422 Unprocessable Entity status code for validation errors
}

    // Check if the current password matches the user's password
    $currentPass = $request->current_password;
    if (!Hash::check($currentPass, $user->password)) {
        return response()->json([
            'error' => 'Current password does not match'
        ], 400);
    }

    // Update the user's password
    $newPass = $request->new_password;
    $user->password = Hash::make($newPass);
    $user->save();

    return response()->json([
        'message' => 'Password updated successfully'
    ], 200);

           
    }
}

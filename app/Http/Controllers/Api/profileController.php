<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class profileController extends Controller {

    public function viewProfile(Request $request) {
        $user = Auth::user();

        return response()->json(new UserResource($user), 200);
    }

    public function editProfile(Request $request)
{
    // Get the authenticated user
    $user = Auth::user();

    // Check if the user is authorized
    if (!$user) {
        return response()->json([
            'error' => 'Unauthorized'
        ], 403);
    }

    $userId = Auth::user()->id; // Get authenticated user's ID
    $user = User::find($userId);

    // Validate the incoming request data
    $validatedData = $request->validate([
        'name' => 'string|max:255',
        'email' => 'string|email|max:255',
        'bio' => 'nullable|string',
    ]);

    // Update user attributes with validated data
    $user->update([
        'name' => $validatedData['name'] ?? $user->name,
        'email' => $validatedData['email'] ?? $user->email,
        // Only set 'bio' if it exists in the request, even if it is null
        'bio' => array_key_exists('bio', $validatedData) ? $validatedData['bio'] : $user->bio,
    ]);


    return response()->json(new UserResource($user), 200);


}
}
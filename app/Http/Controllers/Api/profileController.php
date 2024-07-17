<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{

    public function viewProfile(Request $request)
    {
        $user = Auth::user();

        $this->authorize('show', $user);

        return response()->json(new UserResource($user), 200);
    }

    public function editProfile(ProfileRequest  $request)
    {
        $validatedData = $request->validated();

        $userId = Auth::id();

        $user = User::find($userId);
        $this->authorize('update', $user);

        // Update user attributes with validated data
        $user->update([
            'name' => $validatedData['name'] ?? $user->name,
            'email' => $validatedData['email'] ?? $user->email,
            // Check if password is provided and hash it if so, otherwise keep the existing password
            // 'password' => isset($validatedData['password']) ? Hash::make($validatedData['password']) : $user->password,
            'bio' => $validatedData['bio'] ?? $user->bio,
            'pic' => $validatedData['pic'] ?? $user->pic,
        ]);


        return response()->json(new UserResource($user), 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;

class profileController extends Controller {

    public function viewProfile(Request $request, User $user) {

        return response()->json(['user'=>$user], 200);
    }

    public function editProfile(Request $request, User $user) {

        //check if user is authorized
        if (Auth::user()->id != $user->id) {
            return response()->json([
                'error'=>'Unauthorized'
            ], 403);
        }

        $name = request()->name
    }
}
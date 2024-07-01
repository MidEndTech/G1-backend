<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\signupController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PostController;

use App\Http\Controllers\Api\profileController;

use App\Http\Controllers\Api\likeController;



use App\Models\User;
use App\Models\Post;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//push
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// register 
Route::post('register/{role}', [signupController::class, 'register'])->name('register/{role}');

//login
Route::post('login', [LoginController::class, 'loginUser'])->name('login');

//index
Route::get('/posts', [PostController::class, 'index']);


Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('posts')->name('posts.')->controller(PostController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/{post}', 'show')->name('show');
        Route::put('/{post}', 'update')->name('update');
        Route::delete('/{post}', 'destroy')->name('destroy');
    });
});

Route::middleware('auth:sanctum')->post('posts/{post}/like', [likeController::class, 'likePost'])->name('like');
Route::middleware('auth:sanctum')->post('posts/{post}/unlike', [likeController::class, 'unLike'])->name('unLike');


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [SignupController::class, 'logout'])->name('logout');

    // User routes
    Route::get('user/posts', function (Request $request) {
        return $request->user()->posts;
    });


    // Admin routes
    Route::middleware('admin')->group(function () {

        Route::get('admin/users', function () {
            return User::all();
        });

        Route::get('admin/posts', function () {
            return Post::all();
        });
    });
});

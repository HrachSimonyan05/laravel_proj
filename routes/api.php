<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UsersController;
use App\Http\Controllers\API\FollowerController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\PostImageController;
use App\Http\Controllers\API\AuthController;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\Http\Controllers\ApproveAuthorizationController;
use Laravel\Passport\Http\Controllers\DenyAuthorizationController;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;
use Laravel\Passport\Http\Controllers\TransientTokenController;

Route::post('/oauth/token', [AccessTokenController::class, 'issueToken'])
    ->middleware(['throttle']);

Route::get('/oauth/authorize', [AuthorizationController::class, 'authorize']);
Route::post('/oauth/authorize', [ApproveAuthorizationController::class, 'approve']);
Route::delete('/oauth/authorize', [DenyAuthorizationController::class, 'deny']);

Route::post('/oauth/token/refresh', [TransientTokenController::class, 'refresh']);

Route::post('/oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'store']);
Route::get('/oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'index']);
Route::delete('/oauth/personal-access-tokens/{token_id}', [PersonalAccessTokenController::class, 'destroy']);
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register',[UsersController::class,'store']);


Route::get('users',[UsersController::class,'index']);
Route::delete('users',[UsersController::class,'destroy']);


Route::middleware('jwt')->group(function () {
    Route::post('users/follow',[FollowerController::class,'store']);

    Route::get('post',[PostController::class,'index']);
    Route::post('post',[PostController::class,'store']);
    Route::delete('post',[PostController::class,'destroy']);

    Route::get('post/image',[PostImageController::class,'index']);
    Route::post('post/image',[PostImageController::class,'store']);
    Route::delete('post/image',[PostImageController::class,'destroy']);

    Route::post('post/like',[LikeController::class,'store']);

    Route::post('post/comment',[CommentController::class,'store']);
    Route::delete('post/comment',[CommentController::class,'destroy']);
    Route::get('user', [AuthController::class, 'getUser']);
    Route::post('logout', [AuthController::class, 'logout']);
});

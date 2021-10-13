<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('register', [AuthController::class, 'register'])->name('api:register');
Route::post('login', [AuthController::class, 'login'])->name('api:login');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('get_posts', [UserController::class, 'getPosts'])->name('api:get_posts');
    Route::get('get_one_post/{uid}', [UserController::class, 'get_one_post'])->name('api:get_one_post');
    Route::get('get_user_profile', [UserController::class, 'getUserProfile'])->name('api:get_user_profile');
    Route::get('get_User_Profile_Media', [UserController::class, 'get_User_Profile_Media'])->name('api:get_User_Profile_Media');
    Route::post('search/{keyword?}', [UserController::class, 'search'])->name('api:search');
    Route::post('edit_profile', [UserController::class, 'edit_profile'])->name('api:edit_profile');
    Route::get('get_challenges', [UserController::class, 'get_challenges'])->name('api:get_challenges');
    Route::post('challenge', [UserController::class, 'challenge'])->name('api:challenge');
    Route::post('challenge_done', [UserController::class, 'challenge_done'])->name('api:challenge_done');
    Route::post('upload_media', [UserController::class, 'uploadMedia'])->name('api:upload_media');
    Route::get('get_gifts', [UserController::class, 'get_gifts'])->name('api:get_gifts');
    Route::post('buy_gifts', [UserController::class, 'buy_gifts'])->name('api:buy_gifts');
    Route::post('gift_user', [UserController::class, 'gift_user'])->name('api:gift_user');
    Route::post('follow', [UserController::class, 'follow'])->name('api:follow');
    Route::post('unfollow', [UserController::class, 'unFollow'])->name('api:unfollow');
});

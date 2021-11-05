<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PostsController;
use App\Http\Controllers\API\ChatsController;

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
    Route::get('current_user', [UserController::class, 'currentUser'])->name('api:current_user');
    Route::get('get_user_profile/{uid}', [UserController::class, 'getUserProfile'])->name('api:get_user_profile');
    Route::get('get_user_Posts', [UserController::class, 'get_user_Posts'])->name('api:get_user_Posts');
    Route::get('get_User_Profile_Media', [UserController::class, 'get_User_Profile_Media'])->name('api:get_User_Profile_Media');
    Route::post('search/{keyword?}', [UserController::class, 'search'])->name('api:search');
    Route::post('edit_profile', [UserController::class, 'edit_profile'])->name('api:edit_profile');
    Route::get('get_challenges', [UserController::class, 'get_challenges'])->name('api:get_challenges');
    Route::post('challenge', [UserController::class, 'challenge'])->name('api:challenge');
    Route::post('challenge_done', [UserController::class, 'challenge_done'])->name('api:challenge_done');
    Route::post('upload_media', [UserController::class, 'uploadMedia'])->name('api:upload_media');
    Route::get('get_stories', [UserController::class, 'get_stories'])->name('api:get_stories');
    Route::get('get_gifts', [UserController::class, 'get_gifts'])->name('api:get_gifts');
    Route::post('buy_gifts', [UserController::class, 'buy_gifts'])->name('api:buy_gifts');
    Route::post('gift_user', [UserController::class, 'gift_user'])->name('api:gift_user');
    Route::post('follow', [UserController::class, 'follow'])->name('api:follow');
    Route::post('unfollow', [UserController::class, 'unFollow'])->name('api:unfollow');
    Route::post('block', [UserController::class, 'block'])->name('api:block');
    Route::post('unblock', [UserController::class, 'unblock'])->name('api:unblock');

    Route::post('like_post', [PostsController::class, 'like_post'])->name('api:like_post');
    Route::post('dislike_post', [PostsController::class, 'dislike_post'])->name('api:dislike_post');
    Route::post('add_comment', [PostsController::class, 'add_comment'])->name('api:add_comment');
    Route::get('get_comment/{pid}', [PostsController::class, 'get_comment'])->name('api:get_comment');

    
    Route::get('/getallchat', [ChatsController::class, 'getallchat'])->name('api:getallchat');
    Route::get('/messages/{id}', [ChatsController::class, 'fetchMessages']);
    Route::post('/messages', [ChatsController::class, 'sendMessage']);
    Route::post('/create_chat', [ChatsController::class, 'createChat'])->name('api:create_chat');

    
});
Route::post('/save-token', 'FCMController@index');
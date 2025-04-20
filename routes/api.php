<?php

use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\GroupChatController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('logout', [ProfileController::class, 'logout']);
    
    Route::get('/messages/{user}', [ChatController::class, 'getMessages']);
    Route::post('/messages/send', [ChatController::class, 'sendMessage']);

    // Group Chat
    Route::post('/groups/create', [GroupChatController::class, 'createGroup']);
    Route::get('/groups', [GroupChatController::class, 'myGroups']);
    Route::post('/groups/{group}/messages/send', [GroupChatController::class, 'sendGroupMessage']);
    Route::get('/groups/{group}/messages', [GroupChatController::class, 'getGroupMessages']);
});

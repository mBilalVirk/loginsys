<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\FriendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

    Route::POST('/user/login', [UserController::class,'login']);
    Route::POST('/user/register', [UserController::class,'register']);
    Route::POST('/admin/login', [AdminController::class,'login']);
    
Route::middleware('auth:sanctum')->group(function () {
    Route::POST('/user/logout', [UserController::class,'logout']);
    Route::get('/user/fetch', [UserController::class,'fetch']);
    Route::Post('/user/update', [UserController::class,'update']);
    Route::get('/user/friends', [FriendController::class,'index']);

    Route::post('/user/post',[PostController::class, 'store']);


    Route::get('user/chat/{id}',[MessageController::class, 'chat']);
    Route::post('user/message/send',[MessageController::class, 'create']);
    Route::put('user/message/update/{id}',[MessageController::class, 'update']);
    Route::delete('user/message/delete/{id}',[MessageController::class, 'delete']);


    Route::POST('/admin/logout', [AdminController::class,'logout']);
});

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
    Route::post('/user/comment',[PostController::class, 'createComment']);
    Route::delete('/user/comment/delete/{id}',[PostController::class, 'deleteComment']);


    Route::get('user/chat/{id}',[MessageController::class, 'chat']);
    Route::post('user/message/send',[MessageController::class, 'create']);
    Route::put('user/message/update/{id}',[MessageController::class, 'updateApi']);
    Route::delete('user/message/delete/{id}',[MessageController::class, 'delete']);


    Route::POST('/admin/logout', [AdminController::class,'logout'])->middleware('admin');
   
    Route::GET('/admin/fetch', [AdminController::class,'fetch'])->middleware('admin');
    Route::GET('/admin/stats', [AdminController::class,'countUsersPosts'])->middleware('admin');
    Route::DELETE('/admin/delete/{id}', [AdminController::class,'delete'])->middleware('admin');
    Route::get('/admin/post', [AdminController::class,'userPosts'])->middleware('admin');
    Route::get('/admin/fetchadmin', [AdminController::class,'fetchAdmin'])->middleware('admin');
    Route::post('/admin/create', [AdminController::class,'createNewAdmin'])->middleware('admin');
    Route::get('/admin/search', [AdminController::class,'search'])->middleware('admin');

});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

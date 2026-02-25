<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

    Route::POST('/user/login', [UserController::class,'login']);
    Route::POST('/user/register', [UserController::class,'register']);
    Route::POST('/admin/login', [AdminController::class,'login']);
    
Route::middleware('auth:sanctum')->group(function () {
    Route::POST('/user/logout', [UserController::class,'logout']);
    Route::get('/user/fetch', [UserController::class,'fetch']);
    Route::Post('/user/update', [UserController::class,'update']);





    Route::POST('/admin/logout', [AdminController::class,'logout']);
});

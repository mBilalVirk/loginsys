<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

    Route::POST('/user/login', [UserController::class,'login']);
    Route::POST('/user/register', [UserController::class,'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::POST('/user/logout', [UserController::class,'logout']);
    Route::get('/user/fetch', [UserController::class,'fetch']);
});

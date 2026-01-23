<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;



Route::get('/', function () {
    return view('login');
})->name('login');
Route::get('/register', function () {
    return view('register');
})->name('register');
// Route::get('/login', function () {
//     return view('login');
// })->name('login');

Route::get('/passwordupdate', function () {
    return view('passwordupdate');
})->middleware('auth')->name('passwordupdate');

Route::middleware('auth')->group(function(){
Route::get('/friends', [FriendController::class, 'index'])
    ->name('friends');
    Route::post('/friends/send/{id}', [FriendController::class, 'send'])
    ->name('send.request');
Route::delete('/friends/delete/{id}',[FriendController::class, 'deleteRequest'])
    ->name('delete.request');
Route::post('/friends/accept/{id}',[FriendController::class, 'acceptRequest'])
    ->name('accept.request');
Route::post('/friends/unfriend/{id}',[FriendController::class, 'unFriend'])
    ->name('unfriend.request');
    route::post('/friends/search', [FriendController::class, 'searchUser'])
    ->name('search.user');
});



Route::get('/admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.login');
Route::post('/adminlogin', [App\Http\Controllers\AdminController::class, 'adminlogin'])->name('adminlogin');
Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'countUsersPosts'])
    ->name('admin.dashboard')
    ->middleware('auth');
Route::get('/admin/posts', [App\Http\Controllers\AdminController::class, 'userPosts'])->middleware('auth')->name('admin.posts');
// Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->middleware('auth')->name('admin.dashboard');
Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'fetch'])->middleware('auth')->name('admin.users');
Route::get('/admin/edit/{id}', [App\Http\Controllers\AdminController::class, 'edit'])->middleware('auth')->name('admin.edit');
Route::post('admin/update/{id}',[App\Http\Controllers\AdminController::class, 'userUpdate'])->middleware('auth')->name('admin.userUpdate');
Route::delete('admin/delete/{id}',[App\Http\Controllers\AdminController::class, 'delete'])->middleware('auth')->name('admin.delete');






Route::middleware('auth')->group(function(){
    Route::post('/user/uploadPhoto', [UserController::class, 'updatePhoto'])->name('user.updatePhoto');
    Route::post('/user/updateName', [UserController::class, 'updateName'])->name('user.updateName');
    Route::post('/user/updateEmail', [UserController::class, 'updateEmail'])->name('user.updateEmail');
   
    
});


Route::post('/registerUser', [App\Http\Controllers\UserController::class, 'registerUser'])->name('registerUser');
Route::post('/loginUser', [App\Http\Controllers\UserController::class, 'loginUser'])->name('loginUser');
Route::get('/dashboard', [App\Http\Controllers\UserController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout'])->middleware('auth')->name('logout');
Route::post('/updateProfile', [App\Http\Controllers\UserController::class, 'updateProfile'])->middleware('auth')->name('updateProfile');
Route::post('/updatePassword',[App\Http\Controllers\UserController::class, 'updatePassword'])->middleware('auth')->name('updatePassword');
// Route::get('/admin', [App\Http\Controllers\UserController::class, 'fetch'])
    // ->middleware('auth')
    // ->name('admin.index');
Route::get('/user/profile/{id}', [App\Http\Controllers\UserController::class, 'userProfile'])->middleware('auth')->name('user.profile');



Route::middleware('auth')->group(function(){
    Route::post('/post',[PostController::class, 'store'])->name('post');
    Route::delete('post/delete/{id}',[PostController::class, 'delete'])->name('delpost');
    Route::post('/post/edit/{id}', [PostController::class, 'edit'])->name('editpost');
});



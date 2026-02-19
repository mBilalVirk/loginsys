<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MessageController;



Route::get('/', function () {
    return view('login');
})->name('login');
Route::get('/register', function () {
    return view('register');
})->name('register');


Route::get('/passwordupdate', function () {
    return view('passwordupdate');
})->middleware('auth')->name('passwordupdate');

Route::middleware('auth')->group(function(){
Route::get('/friends', [FriendController::class, 'index'])
    ->name('friends');
    Route::post('/friends/send/{id}', [FriendController::class, 'send'])
    ->name('send.request');
     Route::post('/friends/sendRequestSearch/{id}', [FriendController::class, 'sendRequestSearch'])
    ->name('sendRequestSearch');
Route::delete('/friends/delete/{id}',[FriendController::class, 'deleteRequest'])
    ->name('delete.request');
Route::post('/friends/accept/{id}',[FriendController::class, 'acceptRequest'])
    ->name('accept.request');
Route::post('/friends/unfriend/{id}',[FriendController::class, 'unFriend'])
    ->name('unfriend.request');
    route::any('/friends/search', [FriendController::class, 'searchUser'])
    ->name('search.user');
});



Route::get('/admin/login', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.login');

Route::post('/adminlogin', [App\Http\Controllers\AdminController::class, 'adminlogin'])->name('adminlogin');

Route::middleware(['auth', 'admin'])->group(function () {
    
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'countUsersPosts'])
    ->name('admin.dashboard');
    Route::post('/admin/logout', [App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');
    
Route::get('/admin/posts', [App\Http\Controllers\AdminController::class, 'userPosts'])->name('admin.posts');
// Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->middleware('auth')->name('admin.dashboard');
Route::get('/admin/users', function(){
    return view('admin.users');
    })->name('userView');
    Route::get('/admin/users/data', [App\Http\Controllers\AdminController::class, 'fetch'])->name('admin.users');

Route::get('/admin/edit/{id}', [App\Http\Controllers\AdminController::class, 'edit'])->name('admin.edit');
Route::post('admin/update/{id}',[App\Http\Controllers\AdminController::class, 'userUpdate'])->name('admin.userUpdate');
Route::delete('admin/delete/{id}',[App\Http\Controllers\AdminController::class, 'delete'])->name('admin.delete');
Route::get('/admin/setting', [App\Http\Controllers\AdminController::class, 'setting'])->name('admin.setting');
Route::post('/admin/updateProfile',[App\Http\Controllers\AdminController::class, 'updateProfile'])->name('admin.updateProfile');
// ajax using code
Route::get('/admin/adminsview', function () {
    return view('admin.admins');
})->name('adminsview');
//end of ajax using code
Route::get('/admin/admins',[App\Http\Controllers\AdminController::class, 'fetchAdmin'])->name('admin.admins');
Route::post('admin/createAdmin',[App\Http\Controllers\AdminController::class, 'createNewAdmin'])->name('admin.createNewAdmin');
// Route::delete('admin/comment/{id}', [App\Http\Controllers\AdminController::class,'deleteComment'])->name('comment.delete');
Route::delete('admin/comment/{id}', [App\Http\Controllers\AdminController::class,'deleteComment'])->name('comment.delete');
Route::get('/admin/deleted/data', [App\Http\Controllers\AdminController::class,'fetchTrashedData'])->name('admin.DeletedData');
Route::post('/admin/restore/user/{id}', [App\Http\Controllers\AdminController::class,'restoreUser'])->name('admin.restoreUser');
Route::post('/admin/restore/post/{id}', [App\Http\Controllers\AdminController::class,'restorePost'])->name('admin.restorePost');
Route::post('/admin/restore/comment/{id}', [App\Http\Controllers\AdminController::class,'restoreComment'])->name('admin.restoreComment');
Route::post('/admin/restore/admin/{id}', [App\Http\Controllers\AdminController::class,'restoreAdmin'])->name('admin.restoreAdmin');
Route::Delete('admin/permanentDelete/user/{id}', [App\Http\Controllers\AdminController::class,'permanentDeleteUser'])->name('admin.permanentDeleteUser');
Route::Delete('admin/permanentDelete/post/{id}', [App\Http\Controllers\AdminController::class,'permanentDeletePost'])->name('admin.permanentDeletePost');
Route::Delete('admin/permanentDelete/comment/{id}', [App\Http\Controllers\AdminController::class,'permanentDeleteComment'])->name('admin.permanentDeleteComment');
Route::Delete('admin/permanentDelete/admin/{id}', [App\Http\Controllers\AdminController::class,'permanentDeleteAdmin'])->name('admin.permanentDeleteAdmin');

Route::get('/admin/friend/view',function(){
    return view("admin.friends");
})->name("friendsView");

Route::get('/admin/friends', [App\Http\Controllers\AdminController::class, 'fetchFriends'])->name('admin.friends');
Route::delete('admin/friend/delete/{id}', [App\Http\Controllers\AdminController::class,'deleteFriend'])->name('admin.deleteFriend');
Route::post('/admin/restore/friend/{id}', [App\Http\Controllers\AdminController::class,'restoreFriend'])->name('admin.restoreFriend');
Route::Delete('admin/permanentDelete/friend/{id}', [App\Http\Controllers\AdminController::class,'permanentDeleteFriend'])->name('admin.permanentDeleteFriend');

});






Route::post('/registerUser', [App\Http\Controllers\UserController::class, 'registerUser'])->name('registerUser');
Route::post('/loginUser', [App\Http\Controllers\UserController::class, 'loginUser'])->name('loginUser');
Route::middleware('auth' ,'user')->group(function(){

    
    Route::post('/user/uploadPhoto', [UserController::class, 'updatePhoto'])->name('user.updatePhoto');
    Route::post('/user/updateName', [UserController::class, 'updateName'])->name('user.updateName');
    Route::post('/user/updateEmail', [UserController::class, 'updateEmail'])->name('user.updateEmail');
    Route::delete('/user/comment/{id}',[UserController::class,'deleteComment'])->name('user.deleteComment');
    
    Route::get('/dashboard', [App\Http\Controllers\UserController::class, 'dashboard'])->middleware('auth')->name('dashboard');
    Route::post('/logout', [App\Http\Controllers\UserController::class, 'logout'])->middleware('auth')->name('logout');
    Route::post('/updateProfile', [App\Http\Controllers\UserController::class, 'updateProfile'])->middleware('auth')->name('updateProfile');
    Route::post('/updatePassword',[App\Http\Controllers\UserController::class, 'updatePassword'])->middleware('auth')->name('updatePassword');
    // Route::get('/admin', [App\Http\Controllers\UserController::class, 'fetch'])
        // ->middleware('auth')
        // ->name('admin.index');
    Route::get('/user/profile/{id}', [App\Http\Controllers\UserController::class, 'userProfile'])->middleware('auth')->name('user.profile');
    
});






Route::middleware('auth')->group(function(){
    Route::post('/post',[PostController::class, 'store'])->name('post');
    Route::delete('post/delete/{id}',[PostController::class, 'delete'])->name('delpost');
    Route::post('/post/edit/{id}', [PostController::class, 'edit'])->name('editpost');
    Route::post('/post/comment',[PostController::class, 'createComment'])->name('giveComment');
    Route::post('/post/comment/update/{id}',[PostController::class,'updateComment'])->name('commentUpdate');

});

Route::middleware('auth')->group(function(){
      Route::get('/user/message', [App\Http\Controllers\MessageController::class, 'index'])->name('userMessages');
      Route::post('/user/message/send',[App\Http\Controllers\MessageController::class, 'create'])->name('sendMessage');
      Route::get('/user/chat/friend/{id}', function(){
        return view('user.chat');
      })->name('chat');
      Route::get('user/chat/{id}',[App\Http\Controllers\MessageController::class, 'chat'])->name('chatMessages');
      Route::delete('/user/message/chat/delete/{id}',[App\Http\Controllers\MessageController::class, 'delete'])->name('deleteMessage');
      Route::post('/user/massage/chat/update/{id}',[App\Http\Controllers\MessageController::class, 'update'])->name('messageUpdate');
});
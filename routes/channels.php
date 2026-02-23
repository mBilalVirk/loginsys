<?php
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('chat.{receiverId}', function (User $user, $receiverId) {
    // Only allow the intended receiver to listen
    return true;
    // return (int) $user->id === (int) $receiverId;
});
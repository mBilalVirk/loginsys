<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Friend;
class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = auth()->user();
        // $friends = User::where('id', '!=', $user->id)->get();
        // Get all users except the authenticated user and those who are already friends or have pending requests


        $friends = User::where('id', '!=', $user->id)
                        ->where('role', '!=', 'super_admin')
                        ->where('role', '!=', 'admin')
                        ->whereNull('deleted_at')
        ->whereNotIn('id', function ($query) use ($user) {
            $query->select('friend_id')
                  ->from('friends')
                  ->where('user_id', $user->id)
                  ->whereIn('status', ['pending', 'accepted']);
                          })
                          ->whereNotIn('id', function ($query) use ($user) {
            $query->select('user_id')
                  ->from('friends')
                  ->where('friend_id', $user->id)
                  ->whereIn('status', ['pending', 'accepted']);
                          })
                          ->get();

        // return $friends;
        $sentFriendRequests = User::withWhereHas('sentFriendRequests', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'pending')
                  ->with('receiver' );
        })
        ->whereNull('deleted_at')
        ->get();
  

        $receivedFriendRequests = User::withWhereHas('receivedFriendRequests', function ($query) use ($user) {
            $query->where('friend_id', $user->id)
                  ->where('status', 'pending')
                  ->with('sender' );
        })
        ->whereNull('deleted_at')
        ->get();
    //    echo $receivedFriendRequests;


        // $acceptedFriends = User::withWhereHas('acceptedFriends', function ($query) use ($user) {
        //     $query->where('user_id', $user->id)
        //           ->where('friend_id', $user->id)
        //           ->where('status', 'accepted')
        //            ;
        // })->get();
        $acceptedFriends = Friend::with(['sender', 'receiver'])
    ->where(function($query) use ($user) {
        $query->where('user_id', $user->id)   // requests you sent
              ->orWhere('friend_id', $user->id); // requests you received
    })
    ->where('status', 'accepted')
    ->get();
  
      
       
        
         $rejectedFriends = User::withWhereHas('rejectedFriends', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'rejected');
        })
        ->whereNull('deleted_at')
        ->get();



        return response()->json([
            'potential_friends' => $friends,
            'sent_friend_requests' => $sentFriendRequests,
            'received_friend_requests' => $receivedFriendRequests,
            'accepted_friends' => $acceptedFriends,
            'rejected_friends' => $rejectedFriends,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

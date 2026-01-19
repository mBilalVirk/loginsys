<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $user = auth()->user();
        $friends = User::where('id', '!=', $user->id)->get();
        $friendRequests = User::withWhereHas('sentFriendRequests', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->where('status', 'pending');
        })->get();
       
        return view('user.friends', compact('friends', 'friendRequests', 'user'));
    }
    public function send($friend_id){
        $user = auth()->user();
        $user_id = $user->id;
        Friend::create([
            'user_id' => $user_id,
            'friend_id' => $friend_id,
            'status' => 'pending',
        ]);
        return redirect()->back()->with('status', 'Friend request sent successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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

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



        return view('user.friends', compact('friends', 'sentFriendRequests', 'receivedFriendRequests', 'acceptedFriends', 'rejectedFriends', 'user'));
    }
    public function send($friend_id){
        $user = auth()->user();
        $user_id = $user->id;

        // $alreadyExist = Friend::where('user_id', $user_id)
        //                         ->where('friend_id',$friend_id )
        //                         ->where('status', 'pending')
        //                         ->exists();
        $alreadyExist = friend::where (function($query) use ($user_id,$friend_id){
                $query->where('user_id', $user_id)
                        ->where('friend_id',$friend_id );
        })
        ->orWhere(function($query) use ($user_id,$friend_id)
        {
                $query->where('user_id', $friend_id)
                        ->where('friend_id',$user_id );
        })
        ->where('status', 'pending')
        
        ->exists();

        if($alreadyExist){
            return redirect()->back()->with('status', 'Friend request Already Sent');
        }else{
            Friend::create([
            'user_id' => $user_id,
            'friend_id' => $friend_id,
            'status' => 'pending',
        ]);
        return redirect()->back()->with('status', 'Friend request sent successfully');
        }

        
    }
        public function deleteRequest($id){
                $friend = Friend::findOrFail($id);
                $friend->delete();
                return redirect()->back()->with('status', 'Friend request has been canceled');
        }
    public function acceptRequest(Request $request, $id){
        $acceptFriend = Friend::findOrFail($id);

        $acceptFriend->update([
            'status'=>'accepted'
        ]);
        return redirect()->back()->with('status', 'Friend request has been accepted');
    }
        public function unFriend($id){
                $friend = Friend::findOrFail($id);
               
                $friend->delete();
                return redirect()->back()->with('status', 'Unfriended successfully');
        }
        public function searchUser(Request $request){
                
                // $searchUser = $request->input('searchUser');
                $validatedData = $request->validate([
                    'searchUser'=>'required | String | max:100'
                ],[
                    'searchUser.required'=> 'You must fill it Properly!'
                ]);
                $searchUser = $validatedData['searchUser'];
                $users = User::where(function ($q) use ( $searchUser){
                                $q->where('name', 'LIKE', "%{ $searchUser}%")
                                ->orWhere('email', 'LIKE', "%{$searchUser}%");
                                })      
                            ->where('id', '!=', auth()->user()->id)
                            ->whereNotIn('role',['admin','super_admin'])
                            ->get();
                $user = auth()->user();            
                 $acceptedFriends = Friend::with(['sender', 'receiver'])
                                    ->where(function($query) use ($user) {
                                     $query->where('user_id', $user->id)   
                                    ->orWhere('friend_id', $user->id); 
                                    })
                                    ->where('status', 'accepted')
                                    ->get();
                    $friend_id = [];
                    foreach($acceptedFriends as $friendRequest){
                    // Determine who the friend is
                    if ($friendRequest->user_id == auth()->id()) {
                    $friendUser = $friendRequest->receiver; // You sent the request
                    } else {
                    $friendUser = $friendRequest->sender; // They sent the request
                    }
                    $friend_id[] = $friendUser->id;
                    }
                    // return $friend_id;

       

                return view('user.search', compact('users','friend_id'));
               
                // return view('user.friends', compact('searchUser'));
            }

    // send Friend Request in Search
    public function sendRequestSearch($friend_id){
        $user = auth()->user();
        $user_id = $user->id;
        $alreadyExist = friend::where (function($query) use ($user_id,$friend_id){
                $query->where('user_id', $user_id)
                        ->where('friend_id',$friend_id );
        })
        ->orWhere(function($query) use ($user_id,$friend_id)
        {
                $query->where('user_id', $friend_id)
                        ->where('friend_id',$user_id );
        })
        ->where('status', 'pending')
        ->where('status', 'accepted')
        ->exists();
        
        if($alreadyExist){
            return redirect()->back()->with('status', 'Friend request Already initiate');
        }else{
            Friend::create([
            'user_id' => $user_id,
            'friend_id' => $friend_id,
            'status' => 'pending',
        ]);
        return redirect()->back()->with('status', 'Friend request sent successfully');
        }

        
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

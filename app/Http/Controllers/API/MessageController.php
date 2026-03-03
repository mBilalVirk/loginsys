<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\MessageDeleted;
use App\Events\MessageSent; 
use App\Events\MessageUpdated; 
use App\Models\Friend;
use App\Models\Message;
use App\Models\User;
class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
 public function chat(Request $request,$friend_id){
        
        $user_id = auth()->user()->id;        
        $lastId;
        
        $messages = Message::where(function($q) use ($user_id, $friend_id) {
        $q->where(function($q) use ($user_id, $friend_id) {
            $q->where('sender_id', $user_id)
              ->where('receiver_id', $friend_id);
        })->orWhere(function($q) use ($user_id, $friend_id) {
            $q->where('sender_id', $friend_id)
              ->where('receiver_id', $user_id);
        });
    })
    ->when($request->last_id, function($q) use ($request) {
        $q->where('id', '>', $request->last_id);
    })
    ->with('sender')
    ->orderBy('created_at')
    ->get();

        
        return response()->json([
        'success' => true,
        'message' => 'Messages fetched successfully',
        'data' => $messages
    ], 200);
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

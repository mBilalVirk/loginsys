<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Notifications\NewMessageNotification;

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
    public function create(Request $request)
{
    $validatedData = $request->validate([
        'receiver_id'=> 'required|string',
        'message'=> 'required|string|max:500',
    ],[
        'receiver_id.required'=> 'Receiver does not exist',
        'message.required'=> 'You must type a message'
    ]);

    
    $message = Message::create([
        'sender_id' => auth()->id(),
        'receiver_id' => $validatedData['receiver_id'],
        'message' => $validatedData['message'],
    ]);
    // Notify the receiver
    $receiver = User::find($request->receiver_id);
    $receiver->notify(new NewMessageNotification($message));
    broadcast(new MessageSent($message))->toOthers();
    return response()->json([
        'success' => true,
        'message' => 'Message sent successfully',
        'data' => $message
    ], 201);
}

    /**
     * Display the specified resource.
     */
     public function delete($id){
        $message = Message::findOrFail($id);
        if(!$message){
            return response()->json([
                'success' => false,
                'message' => 'Message not found',
            ], 404);
        }
        $message->delete();
        broadcast(new MessageDeleted($message));
        
        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully',
            'data' => $message
        ], 200);

    }
    public function updateApi(Request $request, $id){

        $message = Message::FindOrFail($id);
        
        if($message->sender_id != auth()->id() ){
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        $validatedData = $request->validate([
            
            'message'=> 'string | required | max:500',
        ],[
            
            'message.required'=> 'You must need type a message'
        ]);
        $message->update($validatedData);

        broadcast(new MessageUpdated($message));
        return response()->json([
            'success' => true,
            'message' => 'Message updated successfully',
            'data' => $message
        ], 200);
    }
}

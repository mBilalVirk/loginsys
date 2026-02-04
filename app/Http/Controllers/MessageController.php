<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index(){
       
         $user = auth()->user();
        //  return $user;
        $friends = Friend::with(['sender', 'receiver'])
            ->where(function($query) use ($user) {
                $query->where('user_id', $user->id)   
                    ->orWhere('friend_id', $user->id); 
            })
            ->where('status', 'accepted')
            ->get();
            // return $friends;
                         
        $messages = Message::all();
       
        return view('user.messages', compact('messages', 'friends'));
    }
    public function chat(Request $request,$friend_id){
        
        $user_id = auth()->user()->id;
        // return $user_id;
        $messages = Message::where(function($q) use ( $user_id,$friend_id){
                            $q  ->where('sender_id',$user_id)
                                ->where('receiver_id',$friend_id);
                             })->orWhere(function($q) use ( $user_id,$friend_id){
                            $q  ->where('sender_id',$friend_id)
                                ->where('receiver_id',$user_id);
                            })
                            ->orderBy('created_at')
                            ->get();
        // return $messages;
        return view('user.chat',compact('messages'));
    }
    public function create(Request $request){
        $validatedData = $request->validate([
            'receiver_id'=> 'string | required',
            'message'=> 'string | required | max:500',
        ],[
            'receiver_id.required'=> 'Receiver does not exist',
            'message.required'=> 'You must need type a message'
        ]);
        Message::create(
            [
                'sender_id' => auth()->id(),
                'receiver_id' => $validatedData['receiver_id'],
                'message' => $validatedData['message'],
            ]
        );

        return redirect()->back();
    }
    public function delete($id){
        $message = Message::findOrFail($id);
        $message->delete();
        return redirect()->back();

    }
    public function update(Request $request, $id){

        $message = Message::FindOrFail($id);
        $validatedData = $request->validate([
            
            'message'=> 'string | required | max:500',
        ],[
            
            'message.required'=> 'You must need type a message'
        ]);
        $message->update($validatedData);
        return redirect()->back();
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    
    public $messageId;
    protected $senderId;
    protected $receiverId; 

    public function __construct($message)
    {
        $this->messageId = $message->id;
        $this->senderId = $message->sender_id;
        $this->receiverId = $message->receiver_id;
    }

    
    public function broadcastOn(): array
    {
         return [
            new PrivateChannel('chat.' . $this->senderId),
            new PrivateChannel('chat.' . $this->receiverId),
        ];
    }
    
    public function broadcastAs()
    {
        return 'message.deleted';
    }
}

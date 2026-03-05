<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewMessageNotification extends Notification
{
    use Queueable;

    public $message; // the message object

    /**
     * Create a new notification instance.
     */
    public function index()
    {
        $user = auth()->user();
        $notifications = $user->unreadNotifications; // only unread
        return response()->json($notifications);
    }
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Delivery channels
     */
    public function via($notifiable)
    {
        // Send to database and broadcast for real-time
        return ['database', 'broadcast'];
    }

    /**
     * Store notification in database
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message->message,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
        ];
    }

    /**
     * Broadcast the notification (Laravel Echo)
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->message->message,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
        ]);
    }

    /**
     * Optional array representation
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->message->message,
            'sender_id' => $this->message->sender_id,
        ];
    }
}
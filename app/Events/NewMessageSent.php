<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use Illuminate\Support\Facades\Log;

class NewMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
        Log::info('NewMessageSent Event Constructor', ['message' => $message->toArray()]);
    }

    public function broadcastOn(): array
    {
        Log::info('Broadcasting on channel', ['channel' => 'chat']);
        return [
            new Channel('chat'), // Changed to public channel for testing
        ];
    }

    public function broadcastWith(): array
    {
        $data = [
            'id' => $this->message->id,
            'message' => $this->message->message,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
        Log::info('Broadcasting data', $data);
        return $data;
    }

    public function broadcastAs()
    {
        Log::info('Broadcasting as', ['event' => 'new-message']);
        return 'new-message';
    }
}

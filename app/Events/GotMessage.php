<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GotMessage implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;


    public function __construct( public Message $message)
    {
        //
    }

    public  function  broadcastOn(): PrivateChannel
    {
        return new PrivateChannel("chat_{$this->message->sender_id}_{$this->message->receiver_id}");

    }

    public  function  broadcastWith(): array
    {
        return [
            'messages'=> $this->message
        ];

    }

}

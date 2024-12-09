<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GotMessage implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct( public  $message)
    {
        //
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.0-0');
    }

    public  function broadcastWith(): array
    {
        return [
            'message' => $this->message,
        ];
    }


}

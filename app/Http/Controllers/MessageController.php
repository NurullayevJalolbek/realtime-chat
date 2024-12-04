<?php

namespace App\Http\Controllers;

use App\Events\GotMessage;
use App\Jobs\SendMessage;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function sendMessage(Request $request): \Illuminate\Http\JsonResponse
    {
        $message = Message::create([
            'sender_id' => $request->input('sender_id'),
            'receiver_id' => $request->input('receiver_id'),
            'text' => $request->input('message'),
        ]);

        broadcast(new GotMessage($message->toArray()));

        return response()->json([
            'message_sent' => true,
        ]);


    }

    public function index($sender_id, $receiver_id)
    {
//        dump( $sender_id, $receiver_id);


        $messages = Message::where(function ($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $sender_id)
                ->where('receiver_id', $receiver_id);
        })
            ->orWhere(function ($query) use ($sender_id, $receiver_id) {
                $query->where('receiver_id', $sender_id)
                    ->where('sender_id', $receiver_id);
            })
            ->orderBy('created_at', 'asc')
            ->get();


        return response()->json([
            $messages,
        ]);

    }


}

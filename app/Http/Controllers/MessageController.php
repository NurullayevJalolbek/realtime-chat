<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
//        dump($request->all());
        $message = new Message();
        $message->sender_id = $request->input('sender_id');
        $message->receiver_id = $request->input('receiver_id');
        $message->text = $request->input('text');
        $message->save();

        $receiverUSER = User::find($request->input('receiver_id'));

        $messages = Message::where(function ($query) use ($receiverUSER) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $receiverUSER->id);
        })
            ->orWhere(function ($query) use ($receiverUSER) {
                $query->where('receiver_id', Auth::id())
                    ->where('sender_id', $receiverUSER->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        if ($messages) {
            return response()->json([
                'message_sent' => true,
                'messages' => $messages,
            ]);
        }

        // Agar xatolik yuz bersa
        return response()->json(['error' => 'Xatolik yuz berdi'], 400);
    }



}

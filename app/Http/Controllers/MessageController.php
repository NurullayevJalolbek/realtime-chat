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
        dd($request->all());

        $message = new Message();
        $message->sender_id = Auth::id();  // Hozirgi foydalanuvchining IDsi
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
            ->orderBy('created_at', 'asc')  // Yozishmalarni vaqt bo'yicha tartiblash
            ->get();


        $receiverUSER = User::find($request->input('receiver_id'));

        $users = User::all() ->except(Auth::user()->id);


        if ($message) {
//            dd($messages ->toArray());
            return view('chat', [
                'receiverUSER' => $receiverUSER,
                'messages' => $messages,
                'users' => $users,
                "message_sent" => true
            ]);

        }
        return "Xatolik yuz berdi";



    }


}

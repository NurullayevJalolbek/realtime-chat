<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($user_id)
    {
        $users = User::all()->except(Auth::user()->id);

        $senderUSER = User::find(Auth::id());

        $receiverUSER = User::find($user_id);

        $messages = Message::where(function ($query) use ($receiverUSER) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $receiverUSER->id);
        })
            ->orWhere(function ($query) use ($receiverUSER) {
                $query->where('receiver_id', Auth::id())
                    ->where('sender_id', $receiverUSER->id);
            })
            ->orderBy('created_at', 'asc') // Yozishmalarni vaqt bo'yicha tartiblash
            ->get();

        return response()->json([
            'receiverUSER' => $receiverUSER ? $receiverUSER->toArray() : null, // null bilan tekshirish
//            'users' => $users->toArray(),
            'senderUSER' => $senderUSER ? $senderUSER->toArray() : null,
            'messages' => $messages->toArray()
        ]);
    }



//        return view('chat', ['receiverUSER' => $receiverUSER, 'users' => $users, 'messages' => $messages]);




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

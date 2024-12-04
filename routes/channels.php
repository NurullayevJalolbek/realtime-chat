<?php


use Illuminate\Support\Facades\Broadcast;

Broadcast::channel( 'App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{senderID}.{receiverID}', function ($user, $senderID, $receiverID) {
    return true;
//    return (int) $user->id === (int) $senderID || (int) $user->id === (int) $receiverID;
});

<?php


use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat_{senderId}_{receiverId}', function ($user, $senderId, $receiverId) {
    return true;
});

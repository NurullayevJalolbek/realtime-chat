<?php


use Illuminate\Support\Facades\Broadcast;

Broadcast::channel( 'App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{sortIds}-{id}', function ($user, $sortIds, $id) {

    return true;
});

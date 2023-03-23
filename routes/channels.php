<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
use App\Models\ParentModel;
use App\User;
use \Chat as Chat;



Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('mc-chat-conversation.{conversationId}', function ($user, $conversationId) {
    $conversation =  Chat::conversations()->getById($conversationId);
    foreach ($conversation->getParticipants() as $participant)
    {
        if ($participant->id == $user->id 
            && get_class($participant) == get_class($user))
        {
            return true;
        }
    }
    return false;
});

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

//Broadcast::routes();
Broadcast::routes(['middleware' => ['auth:api']]);

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

Broadcast::channel('TestPresenceChannel', function (\App\Models\User $user) {
    return ['id' => $user->id, 'name' => $user->name, 'email' => $user->email];
});

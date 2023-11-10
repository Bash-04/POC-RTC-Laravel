<?php

use App\Models\Item;
use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('Parkingspot.{user_id}', function ($user, $user_id) {
    return Item::whereNotIn('id', function ($query) use ($user) {
        $query->select('item_id')
        ->from('reserved_items')
        ->where('user_id', '!=', $user->id);
    })->get();;
});
Broadcast::channel('channel-name', function ($user, $user_id) {
    return "lol";
});
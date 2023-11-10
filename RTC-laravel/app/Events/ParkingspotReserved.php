<?php

namespace App\Events;

use App\Models\Item;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ParkingspotReserved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id; // Define a public property to hold the user ID.
    public $parkingspot; // Define a public property to hold the user ID.

    /**
     * Create a new event instance.
     *
     * @param int $user_id
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
        $this->parkingspot = Item::whereNotIn('id', function ($query) use ($user_id) {
            $query->select('item_id')
            ->from('reserved_items')
            ->where('user_id', '!=', $user_id);
        })->get();;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [new PrivateChannel('Parkingspot.{user_id}')];
    }

    public function broadcastAs()
    {
        return 'parkingspot-availability';
    }
}

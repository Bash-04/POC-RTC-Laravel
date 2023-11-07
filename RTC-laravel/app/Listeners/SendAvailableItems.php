<?php

namespace App\Listeners;

use App\Events\ItemReserved;
use App\Models\Item;
use App\Models\ReserveItem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAvailableItems
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ItemReserved $event): void
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Events\ParkingspotReserved;
use App\Models\Item;
use App\Models\ReserveItem;
use Error;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ItemController extends Controller
{
    //
    public function CreateItem(Request $request){
        // Get the authenticated user's ID
        
        if (!$request->validate([
            'user_id' => 'required',
            'name' => 'required',
            ]) || Item::where('name', $request->name)->exists()) {
                return "Validation Error";
        }
        else {
            $user_id = $request->user_id;
            $item = Item::create([
            'name' => $request->name,
        ]);
           
            // Pass the user_id to the event
            event(new ParkingspotReserved($user_id));
        }
       
        return $item;
    }
    
    public function GetItems(Request $request){
        $items = Item::all();
        return $items;
    }

    public function streamGetAvailableItems(string $user_id){
        return new StreamedResponse(function () use ($user_id) {
            $lastAvailableItems = [];
            while (true) {
                try {
                    // get all available items that are reservable or reserved by the user
                    $availableItems = Item::whereNotIn('id', function ($query) use ($user_id) {
                        $query->select('item_id')
                        ->from('reserved_items')
                        ->where('user_id', '!=', $user_id);
                    })->get();

                    if ($availableItems !== null && json_encode($lastAvailableItems) !== json_encode($availableItems)) {
                        echo "data 1: " . json_encode($availableItems) . "\n\n";
                        
                        $lastAvailableItems = $availableItems->toArray();
                    }
                    else{
                        echo "data: " . json_encode(['error' => 'No new items']) . "\n\n";
                    }
                    
                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();

                    // if client connection is lost, then stop sending data
                    if (connection_aborted()) {
                        break;
                    }
                    sleep(.5); // Sleep for .5 seconds (500 milliseconds)
                } catch (Exception $e) {
                    echo "data: " . json_encode(['error' => $e->getMessage()]) . "\n\n";
                    ob_flush();
                    flush();
                    break;
                }
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
        ]);
    }

    public function ReserveItem(Request $request){
        if ($request->validate([
            'user_id' => 'required',
            'item_id' => 'required',
        ]) === false) {
            return "Validation Error";
        }
        
        $reserveItem = ReserveItem::create([
            'user_id' => $request->user_id,
            'item_id' => $request->item_id,
        ]);
        
        return $reserveItem;
    }
    
    public function DeleteReservation(){
        return ReserveItem::truncate();
    }
}

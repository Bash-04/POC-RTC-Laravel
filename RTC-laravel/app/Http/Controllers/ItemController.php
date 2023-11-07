<?php

namespace App\Http\Controllers;

use App\Events\ItemReserved;
use App\Models\Item;
use App\Models\ReserveItem;
use Error;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ItemController extends Controller
{
    //
    public function CreateItem(Request $request)
    {
        if ($request->validate([
            'name' => 'required',
        ]) === false) {
            return "Validation Error";
        }
        
        $item = Item::create([
            'name' => $request->name,
        ]);
        
        return $item;
    }

    public function GetItems(Request $request)
    {
        $items = Item::all();
        return $items;
    }

    public function streamGetAvailableItems(Request $request)
    {
        return new StreamedResponse(function () use ($request) {
            while (true) {
                try {
                    $user_id = $request->user_id;
                    
                    $availableItems = Item::whereNotIn('id', function ($query) use ($user_id) {
                        $query->select('item_id')
                        ->from('reserved_items')
                        ->where('user_id', '!=', $user_id);
                    })->orWhereIn('id', function ($query) use ($user_id) {
                        $query->select('item_id')
                        ->from('reserved_items')
                        ->where('user_id', $user_id);
                    })->get();

                    echo "data: " . json_encode($availableItems) . "\n\n";
                    
                    ob_flush();
                    flush();

                    if (connection_aborted()) {
                        break;
                    }
                    sleep(5); // Sleep for 5 seconds (5000 milliseconds)
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
    
    public function DeleteReservation(Request $request){
        if ($request->validate([
            'user_id' => 'required',
            'item_id' => 'required',
        ]) === false) {
            return "Validation Error";
        }

        event(new ItemReserved($request->user_id, $request->item_id));
        
        $reserveItem = ReserveItem::destroy([
            'user_id' => $request->user_id,
            'item_id' => $request->item_id,
        ]);
        
        return $reserveItem;
    }
}

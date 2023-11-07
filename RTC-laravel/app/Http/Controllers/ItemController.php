<?php

namespace App\Http\Controllers;

use App\Events\ItemReserved;
use App\Models\Item;
use App\Models\ReserveItem;
use Error;
use Illuminate\Http\Request;

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

    public function GetAvailableItems(Request $request)
    {
        $items = Item::all();
        $reservedItems = ReserveItem::all();
        $availableItems = $items->whereNotIn('id', $reservedItems->where('user_id', '!=', request()->user_id)->pluck('id'));

        return $availableItems;
    }

    public function ReserveItem(Request $request){
        if ($request->validate([
            'user_id' => 'required',
            'item_id' => 'required',
        ]) === false) {
            return "Validation Error";
        }

        event(new ItemReserved($request->user_id, $request->item_id));
        
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

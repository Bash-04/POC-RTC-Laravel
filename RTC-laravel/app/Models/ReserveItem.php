<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReserveItem extends Model
{
    use HasFactory;

    protected $table = 'reserved_items';

    protected $fillable = [
        'user_id',
        'item_id',
    ];
}

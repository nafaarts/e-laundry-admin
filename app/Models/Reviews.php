<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;

    protected $fillable = [
        'laundry_id',
        'user_id',
        'order_id',
        'rating',
        'comments',
    ];

    function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }

    function order()
    {
        return $this->belongsTo(Order::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }
}

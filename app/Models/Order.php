<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'laundry_id',
        'status',
        'with_pick_up',
        'address_id',
        'lat',
        'long',
        'is_paid',
        'is_pickedup',
        'is_viewed'
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }

    function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    function review()
    {
        return $this->hasOne(Reviews::class);
    }

    function getTotalPrice()
    {
        return $this->details->reduce(function ($total, $item) {
            return $total += $item->price;
        });
    }

    function getTotalWeight()
    {
        return $this->details->reduce(function ($total, $item) {
            return $total += $item->weight;
        });
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class);
    }
}

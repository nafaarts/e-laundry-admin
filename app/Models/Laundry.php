<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laundry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'no_izin',
        'address',
        'district',
        'city',
        'lat',
        'long',
        'image',
        'has_pickup'
    ];

    function scopeFilter($query)
    {
        if (request()->has('search') && request('search') != null) {
            $query->where('name', 'LIKE', '%' . request('search') . '%');
        }
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function services()
    {
        return $this->hasMany(LaundryService::class);
    }

    function reviews()
    {
        return $this->hasMany(Reviews::class);
    }

    function getRating()
    {
        if ($this->reviews->count() == 0) return 0;
        return round($this->reviews->reduce(function ($total, $item) {
            return $total += $item->rating;
        })  / $this->reviews->count());
    }

    function orders()
    {
        return $this->hasMany(Order::class);
    }
}

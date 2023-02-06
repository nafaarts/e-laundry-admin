<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaundryService extends Model
{
    use HasFactory;

    protected $fillable = [
        'laundry_id',
        'name',
        'description',
        'unit',
        'price',
        'icon'
    ];

    function laundry()
    {
        return $this->belongsTo(Laundry::class);
    }
}

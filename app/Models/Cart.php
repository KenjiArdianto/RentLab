<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    /** @use HasFactory<\Database\Factories\LocationFactory> */
    use HasFactory;

    protected $fillable = [
        'location'
    ];

    public function driver() 
    {
        return $this->hasMany(Driver::class);
    }

    public function vehicle() 
    {
        return $this->hasMany(Vehicle::class);
    }
}

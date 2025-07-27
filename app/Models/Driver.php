<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    /** @use HasFactory<\Database\Factories\DriverFactory> */

    protected $fillable = [
        'name',
        'location_id',
        'image',
    ];

    use HasFactory;

    public function location() 
    {
        return $this->belongsTo(Location::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}

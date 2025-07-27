<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleName extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleNameFactory> */
    use HasFactory;
    
    protected $fillable = [
        'name',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}

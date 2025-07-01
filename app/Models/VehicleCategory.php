<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleCategory extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleCategoryFactory> */
    use HasFactory;

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class);
    }
}

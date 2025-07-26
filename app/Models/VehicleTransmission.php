<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleTransmission extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleTransmissionFactory> */
    use HasFactory;

    protected $fillable = [
        'transmission'
    ];
    
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}

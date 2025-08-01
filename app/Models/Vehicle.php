<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;

    protected $fillable = [
        'vehicle_type_id',
        'vehicle_name_id',
        'vehicle_transmission_id',
        'location_id',
        'engine_cc',
        'seats',
        'price',
        'main_image'
    ];

    public function vehicleImages()
    {
        return $this->hasMany(VehicleImage::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function vehicleName()
    {
        return $this->belongsTo(VehicleName::class);
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function vehicleTransmission()
    {
        return $this->belongsTo(VehicleTransmission::class);
    }

    public function vehicleCategories()
    {
        return $this->belongsToMany(VehicleCategory::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function vehicleReview()
    {
        return $this->hasMany(VehicleReview::class);
    }
}

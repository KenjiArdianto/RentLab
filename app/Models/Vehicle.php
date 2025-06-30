<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'year',
        'image',
        'engine_cc',
        'transmission_type',
        'type',
        'vehicle_category',
        'vehicle_location',
    ];

    public function transactions()
    {
        // Pastikan nama model Anda adalah Transaction
        // Laravel akan otomatis mengasumsikan foreign key adalah vehicle_id
        return $this->hasMany(Transaction::class);
    }
}

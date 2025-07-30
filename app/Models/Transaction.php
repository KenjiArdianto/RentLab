<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'external_id',
        'vehicle_id',
        'user_id',
        'driver_id',
        'start_book_date',
        'end_book_date',
        'return_date',
        'status',
    ];

    public function transactionStatus()
    {
        return $this->belongsTo(TransactionStatus::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function userReview()
    {
        return $this->hasMany(UserReview::class);
    }

    public function vehicleReview()
    {
        return $this->hasOne(VehicleReview::class);
    }

    protected function VehiclePrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->vehicle || !$this->vehicle->price) {
                    return 0;
                }

                $start = Carbon::parse($this->start_book_date);
                $end = Carbon::parse($this->end_book_date);

                $days = $start->diffInDays($end);

                $vehiclePrice = $days * $this->vehicle->price;

                return $vehiclePrice;
            }
        );
    }

    protected function driverFee(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->driver_id ? 50000 : 0
        );
    }

    protected function totalPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->vehicle_price + $this->driver_fee
        );
    }
}
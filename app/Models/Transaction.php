<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

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
        return $this->hasMany(VehicleReview::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'transaction_status_id',
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

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
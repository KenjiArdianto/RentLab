<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class VehicleReview extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleReviewFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = [
        'comment', 
        'rate',
        'user_id',
        'vehicle_id',
        'transaction_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['comment', 'rate'])
            ->useLogName('vehicle_review_model')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    protected static function booted()
    {
        static::created(function ($vehicleReview) {
            $transaction = $vehicleReview->transaction;
            
            // Cek apakah review dari admin juga sudah ada
            if ($transaction && $transaction->userReview->isNotEmpty()) {
                $transaction->transaction_status_id = 6;
                $transaction->save();
            }
        });
    }
}
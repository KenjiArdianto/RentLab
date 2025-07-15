<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleReview extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleReviewFactory> */
    use HasFactory;

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
    protected $fillable = [
        'transaction_id',
        'user_id',
        'vehicle_id',
        'rate',
        'comment',
    ];

    protected $table = 'vehicle_review';
}

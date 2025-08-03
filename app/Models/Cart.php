<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\CartFactory> */
    use HasFactory, LogsActivity;
    protected $fillable = [
        "vehicle_id",
        'start_date',
        "end_date",
        'user_id',
        "subtotal"
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['vehicle_id', 'start_date', 'end_date', 'subtotal', 'user_id'])
            ->useLogName('cart_model')
            ->logOnlyDirty() // Only log changed values on update
            ->dontSubmitEmptyLogs(); // Don’t log if nothing changed
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}

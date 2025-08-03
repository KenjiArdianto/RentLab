<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class VehicleImage extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleImageFactory> */
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['image'])
            ->useLogName('vehicle_image_model')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $fillable = [
        'image',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}

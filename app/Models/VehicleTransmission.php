<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class VehicleTransmission extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleTransmissionFactory> */
    use HasFactory;

    protected $fillable = [
        'transmission'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['transmission'])
            ->useLogName('vehicle_transmission_model')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class VehicleName extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleNameFactory> */
    use HasFactory, LogsActivity;
    
    protected $fillable = [
        'name',
    ];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name']) // only log if 'name' changes
            ->useLogName('vehicle_name_model')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

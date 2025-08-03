<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Location extends Model
{
    /** @use HasFactory<\Database\Factories\LocationFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = [
        'location'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['location'])
            ->useLogName('location_model')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function driver() 
    {
        return $this->hasMany(Driver::class);
    }

    public function vehicle() 
    {
        return $this->hasMany(Vehicle::class);
    }
}

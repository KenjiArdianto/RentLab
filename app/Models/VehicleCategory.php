<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class VehicleCategory extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleCategoryFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = [
        'category'
    ];

    public function getActivitylogOptions(): LogOptions
{
    return LogOptions::defaults()
        ->logOnly(['category'])
        ->useLogName('vehicle_category_model')
        ->logOnlyDirty()
        ->dontSubmitEmptyLogs();
}

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class Driver extends Model
{
    /** @use HasFactory<\Database\Factories\DriverFactory> */
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'location_id',
        'image',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'location_id', 'image']) // only log changes to these
            ->useLogName('driver_model')                // tag logs with a custom name
            ->logOnlyDirty()                            // log only if values are changed
            ->dontSubmitEmptyLogs();                    // avoid empty logs when nothing changed
    }

    public function location() 
    {
        return $this->belongsTo(Location::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Payment extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'external_id',
                'amount',
                'status',
                'paid_at',
                'payment_method',
                'payment_channel',
                'url',
            ])
            ->useLogName('payment_model')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'external_id',
        'amount',
        'status',
        'paid_at',
        'payment_method',
        'payment_channel',
        'url',
    ];

    /**
     * Get the transactions for the payment.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

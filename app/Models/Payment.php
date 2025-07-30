<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

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
    ];

    /**
     * Get the transactions for the payment.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

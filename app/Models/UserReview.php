<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    /** @use HasFactory<\Database\Factories\UserReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'user_id',
        'transaction_id',
        'comment',
        'rate'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function booted()
    {
        static::created(function ($userReview) {
            $transaction = $userReview->transaction;

            // Cek apakah review dari pengguna juga sudah ada
            if ($transaction && $transaction->vehicleReview) {
                $transaction->transaction_status_id = 6;
                $transaction->save();
            }
        });
    }
}
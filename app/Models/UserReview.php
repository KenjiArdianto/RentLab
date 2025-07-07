<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    /** @use HasFactory<\Database\Factories\UserReviewFactory> */
    use HasFactory;

    protected $table = 'user_review';
    
    protected $fillable = [
        'transaction_id',
        'user_id',
        'admin_id',
        'rate',
        'comment',
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
}

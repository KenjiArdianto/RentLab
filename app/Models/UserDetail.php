<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    //
    protected $fillable = [
        'user_id',
        'fname',
        'lname',
        'phoneNumber',
        'idcardNumber',
        'dateOfBirth',
        'idcardPicture',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

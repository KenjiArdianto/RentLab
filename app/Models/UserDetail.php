<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class UserDetail extends Model
{
    //
    use SoftDeletes;
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

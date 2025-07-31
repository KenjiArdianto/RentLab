<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class UserDetail extends Model
{
    //
    use SoftDeletes, LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'user_id',
                'fname',
                'lname',
                'phoneNumber',
                'idcardNumber',
                'dateOfBirth',
                'idcardPicture'
            ])
            ->useLogName('user_detail_model')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
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

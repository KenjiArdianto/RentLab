<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Testing\Fluent\Concerns\Has;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class UserDetail extends Model
{
    //
    use SoftDeletes, LogsActivity, HasFactory;
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

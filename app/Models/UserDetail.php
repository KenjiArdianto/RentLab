<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
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
                'profilePicture',
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
        'profilePicture',
        'idcardPicture',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function booted(){
        static::deleting(function($detail){
            $detail->deleteImages();
        });
    }

    public function deleteImages(){
        if($this->profilePicture && Storage::disk('public')->exists($this->profilePicture)){
            Storage::disk('public')->delete($this->profilePicture);
        }
        if($this->idcardPicture && Storage::disk('public')->exists($this->idcardPicture)){
            $filename=basename($this->idcardPicture);
            $newPath='deleted/'.$filename;
            if (!Storage::disk('public')->exists('deleted')) {
                Storage::disk('public')->makeDirectory('deleted');
            }

            if(Storage::disk('public')->move($this->idcardPicture,$newPath)){
                $this->idcardPicture=$newPath;
                $this->saveQuietly();
            }
        }

    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function userReview()
    {
        return $this->hasMany(UserReview::class, 'user_id');
    }

    public function reviewsModerated()
    {
        return $this->hasMany(UserReview::class, 'admin_id');
    }

    public function vehicleReview()
    {
        return $this->hasMany(VehicleReview::class);
    }

}

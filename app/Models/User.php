<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'rt_id',
        'total_kg',
        'saldo',
        'nik',
        'alamat',
        'phone',
        'fcm_token',
        'role',
    ];

     public function rt()
    {
        return $this->belongsTo(Rt::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function Withdraw()
    {
        return $this->hasMany(Withdraw::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'rt_id' => 'integer',
    ];
}

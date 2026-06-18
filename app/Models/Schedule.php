<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rt_id',
        'type',
        'title',
        'date',
        'time',
        'alamat',
        'notes',
    ];

    protected $appends = [
        'user_name',
        'rt_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rt()
    {
        return $this->belongsTo(Rt::class);
    }

    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->name : null;
    }

    public function getRtNameAttribute()
    {
        return $this->rt ? $this->rt->name : null;
    }
}

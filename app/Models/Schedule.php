<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable =[
        'user_id',
        'address_id',
        'day',
        'date',
        // 'time',
        // 'status',
    ];
    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    public function periods()
    {
        return $this->hasMany(Period::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

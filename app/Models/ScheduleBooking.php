<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleBooking extends Model
{
    use HasFactory;
    protected $fillable =[
        'booker_id',
        'schedule_id',
        'confirm',
        'attended',
    ];
    public function user()
    {
        return $this->hasMany(User::class);
    }
    // public function scheduleTime()
    // {
    //     return $this->belongsTo(ScheduleTime::class);
    // }
}

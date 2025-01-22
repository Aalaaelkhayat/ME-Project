<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'profile_id',
        'user_id',
        'address_id',
        'schedule_id',
        'period_id',
        'appointment_id',
        'confirm_status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function profile()
    {
        return $this->belongsTo(User::class);
    }
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    public function period()
    {
        return $this->belongsTo(Period::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;
    protected $fillable = [
        'schedule_id',
        'period',
    ];
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

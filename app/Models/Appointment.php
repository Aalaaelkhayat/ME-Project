<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'schedule_id',
        'period_id',
        'time',
        'status',
        'attend',
    ];
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}

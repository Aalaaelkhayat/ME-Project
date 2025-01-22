<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'title',
        'body',
        'date',
        'active',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($advert) {
            $advert->date = date('Y-m-d h:m:s', strtotime(now()));
             // Set created_at_value to the current timestamp

        });
    }
}

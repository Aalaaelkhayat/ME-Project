<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'user_id',
        'title',
        'description',
        'date',
        'active',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function images(){
        return $this->hasMany(Image::class);
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->date = date('Y-m-d h:m:s', strtotime(now()));
            // Set created_at_value to the current timestamp
        });
    }
}

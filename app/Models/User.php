<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'mobile',
        'spelization_id',
        'title',
        'description',
        'countryNameCode',
        'countryPhoneCode',
        'registerationDate',
        'image',
        'active',
        'hide',
        'availableForWork',
        'trusted',
        'token',
        'fcm_token',
        'lock',
        'font_color',
        'background_color',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
    public function socialaccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->token = Str::random(255); // Generate a random token
        });
    }
    // Scope to filter visible users
    public function scopeVisible($query)
    {
        return $query->where('hide', 0);
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
}

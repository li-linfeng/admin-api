<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Filters\UserFilter;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, UserFilter;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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
        return [
            'role' => 'user'
        ];
    }


    public function  friends()
    {
        return $this->hasMany(User::class, 'parent_id', 'id');
    }


    /**
     * 通过分享链接注册的关联用户
     */
    public function  parent()
    {
        return $this->hasOne(User::class, 'id', 'parent_id');
    }


    /**
     * 用户的冥想账户
     */
    public function account()
    {
        return $this->hasOne(UserMuseAccount::class, 'user_id', 'id');
    }


    public function avatar()
    {
        return $this->hasOne(Image::class, 'id', 'avatar_id');
    }


    /**
     * 用户的冥想账户
     */
    public function accountLog()
    {
        return $this->hasMany(UserMuseAccountLog::class, 'user_id', 'id');
    }
}

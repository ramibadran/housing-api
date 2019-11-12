<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Cache;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }
    
    public function setPasswordAttribute($password){
        if ( !empty($password) ) {
            $this->attributes['password'] = bcrypt($password);
        }
    }   
    
    public static function getUserDetails($publicKey){
        return self::where('public_key',$publicKey)->first();
    }
    
    public static function isUserHasApiAccess($username,$identifier,$ip){
        return self::where('username',$username)
                    ->where('secret_key',$identifier)
                    ->where('api_ip_white_list','like',"%$ip%")->count();
    }
    
    public static function getIpList($userId){
        if(Cache::has('user_ip_list_'.$userId)){
            return Cache::get('user_ip_list_'.$userId);
        }else{
            $query = self::where('id',$userId)->select('api_ip_white_list','ip_white_list','status')->first();
            Cache::put('user_ip_list_'.$userId,$query,60*4);
            return $query;
        }
    }
}

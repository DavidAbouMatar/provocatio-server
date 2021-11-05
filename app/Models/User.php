<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Messages;
use Tymon\JWTAuth\Facades\JWTAuth;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_name',
        'last_name',
        'email',
        'password',
        'fcm_token',
        'score',
        'bio',
        'dob',
        'profile_picture_path',
        'gender',
    ];

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

    // public function getFullNameAttribute(){
	// 	return implode(' ', [$this->first_name, $this->last_name]);
	// }
	
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

    public function messages()
    {
        return $this->hasMany(Message::class);
    } 
    

    public function posts(){
        return $this->hasMany('App\Models\Post');
    }
    public function likes(){
        return $this->hasMany('App\Models\Like');
    }
    public function story(){
        return $this->hasOne('App\Models\Story');
    }
    
    
    // user can have many comments as well
    
    public function comments(){
        return $this->hasMany('App\Models\Comment','user_id');
    }

    public function followers(){
     
		return $this->belongsToMany(User::class, 'connections', 'user_id', 'friend_id');
	}

    public function followed(){
     
		return $this->belongsToMany(User::class, 'connections', 'friend_id', 'user_id');
	}

    public function chat(){
        // return $this->belongsToMany(User::class)->pivot(['sender', 'recipient'])->withTimestamps();
       
		return $this->belongsToMany(User::class, 'chats', 'sender', 'recipient')->withPivot('id')->orderBy('created_at');
	}

    public function chats(){
     
		return $this->belongsToMany(User::class, 'chats', 'recipient', 'sender')->withPivot('id')->orderBy('created_at');
	}

    public function challenges(){
     
		return $this->belongsToMany(User::class, 'challenges', 'user_id', 'for_user_id')->withPivot('id')->withPivot('discription')->orderBy('created_at');
	}

    // public function challenges(){
    //     return $this->hasMany('App\Models\Challenge','user_id', 'id');
    // }
  

    public function connections(){
    return $this->belongsToMany(User::class, 'connections', 'user_id' , 'friend_id');

    }
    public function friendconnections(){
        return $this->belongsToMany(User::class, 'connections', 'friend_id' , 'user_id');
    
        }
    public function isFollowing()
        {
            return  $this->friendconnections()->where('user_id', JWTAuth::user()->id);
        }

    // public function followed(){
    //     return $this->belongsToMany(User::class, 'connections', 'friend_id', 'user_id');
    
    //     }
    public function block(){
        return $this->belongsToMany(User::class, 'blocks', 'user_id', 'blocked_id');
    
        }

    public function userProfile(){
		return $this->hasOne(UserProfile::class);
        // return $this->hasOne(UserProfile::class);
	}
}

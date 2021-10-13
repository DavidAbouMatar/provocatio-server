<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
	protected $table = "user_profiles";

      /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'bio',
        'dob',
        'gender',
        'phone_number',
        'profile_picture_path',
    ];

	public function user(){
		return $this->belongsTo('App\Models\User');
	}


}

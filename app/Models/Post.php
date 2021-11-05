<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Facades\JWTAuth;

class Post extends Model
{
	use HasFactory;

	protected $table = "posts";

	public function comments(){
		return $this->hasMany('App\Models\Comment', 'post_id', 'id');
	}
	public function user(){
		return $this->belongsTo('App\Models\User');
	}

	public function likes(){
		return $this->hasMany('App\Models\Like', 'post_id', 'id');
	}
	public function isAuthLiked(){
		return $this->likes()->where('user_id', JWTAuth::user()->id);
	 }

	// public function likes(){
	// 	return $this->hasMany('App\Models\Like', 'post_id', 'id');
	// }


	// protected $appends = 'liked_by_auth_user';
	// // check if user liked the post
	// public function getLikedByAuthUserAttribute()
	// 	{
	// 		$userId = auth()->user()->id;
			
	// 		$like = $this->likes->first(function ($key, $value) use ($userId) {
	// 			return $value->user_id === $userId;
	// 		});
			
	// 		if ($like) {
	// 			return true;
	// 		}
			
	// 		return false;
	// 	}

}

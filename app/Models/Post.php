<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	protected $table = "Posts";

	public function comments(){
		return $this->hasMany('App\Models\Comment', 'post_id', 'id');
	}


}

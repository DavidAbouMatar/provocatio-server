<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
	protected $table = "challenges";


	public function user(){
		return $this->belongsToMany('App\Models\User', 'user_id', 'id');
	}



}

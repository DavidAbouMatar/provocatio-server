<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Like extends Model
{
    use HasFactory;
	protected $table = "likes";

    // posts belongs to one user
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id')->withDefault();
    }
  


	public function posts(){
     
		return $this->belongsTo('App\Models\Post', 'post_id', 'id')->withDefault();
	}


}

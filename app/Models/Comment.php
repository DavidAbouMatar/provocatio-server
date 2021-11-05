<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Comment extends Model
{
    use HasFactory;
	protected $table = "comments";

    // posts belongs to one user
    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
  


	public function posts(){
     
		return $this->belongsTo('App\Models\Post', 'post_id', 'id');
	}



}

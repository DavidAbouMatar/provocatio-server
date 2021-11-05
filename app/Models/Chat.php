<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender',
        'recipient',
       
    ];


    protected $table = "chats";

    public function messages()
    {
        return $this->hasMany('App\Models\Message');
        // return $this->hasMany(Message::class);
    } 
    public function user()
    {
        return $this->belongsToMany('App\Models\User');
        // return $this->hasMany(Message::class);
    } 
    

}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Post;
class comment extends Model
{
    //
    protected $fillable = [ 'user_id','comments', 'post_id'];
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
     public function post(){
        return $this->belongsTo(Post::class, 'post_id');
    }
}


<?php

namespace App\Models;


use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [ 'user_id','comment', 'post_id','parent_id'];
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
     public function post(){
        return $this->belongsTo(Post::class, 'post_id');
    }
    public function commentWithComment(){
        return $this->hasMany(Comment::class,'parent_id')->with('commentWithComment');
    }
}


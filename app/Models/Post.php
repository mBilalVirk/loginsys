<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Comment;
class Post extends Model
{
    use SoftDeletes;
    //  public $timestamps = false;
     protected $fillable = [
        'user_id',
        'content',
        'photo',
     ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments(){
        return $this->hasMany(comment::class);
    }

}

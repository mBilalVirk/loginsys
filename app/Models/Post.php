<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
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

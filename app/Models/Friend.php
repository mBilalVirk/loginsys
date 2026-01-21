<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Friend extends Model
{
    protected $fillable = [
        'user_id',
        'friend_id',
        'status', 
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
    public function friends()
    {
        return $this->belongsTo(User::class, 'user_id', 'friend_id');
    }

}

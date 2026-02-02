<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Friend extends Model
{
    use SoftDeletes;
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

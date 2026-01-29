<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Post;
use App\Models\Friend;
use App\Models\Comment;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use SoftDeletes;
    use HasFactory, Notifiable;
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'role',
    ];
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function sentFriendRequests(){
        return $this->hasMany(Friend::class, 'user_id');
    }
    public function receivedFriendRequests(){
        return $this->hasMany(Friend::class, 'friend_id');
    }
    public function acceptedFriends(){
        return $this->hasMany(Friend::class, 'user_id')->where('status', 'accepted');
    }
    public function rejectedFriends(){
        return $this->hasMany(Friend::class, 'user_id')->where('status', 'rejected');
    }
     public function comments(){
        return $this->hasMany(Comment::class);
    }
    // public function friends(){
    //     return $this->hasMany(Friend::class, 'user_id');
    // }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            
            'password' => 'hashed',
        ];
    }
}

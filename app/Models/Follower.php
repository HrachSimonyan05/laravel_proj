<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    protected $fillable = [
        'followed_user_id',
        'follower_id',
    ];

    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    // User who is being followed
    public function followedUser()
    {
        return $this->belongsTo(User::class, 'followed_user_id');
    }
}

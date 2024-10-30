<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description_ARM',
        'description_RUS',
        'description_ENG',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function images(){
        return $this->hasMany(PostImage::class);
    }

    public function likes(){
        return $this->hasMany(Like::class,'liked_post_id');
    }

    public function comments(){
        return $this->hasMany(Comment::class,);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model {
    use HasFactory;

    protected $fillable = ['user_id','body','image_path'];

    public function user(){ return $this->belongsTo(User::class); }
    public function comments(){ return $this->hasMany(Comment::class)->latest(); }
    public function likes(){ return $this->hasMany(Like::class); }

    public function isLikedBy(?User $user): bool {
        return $user ? $this->likes->contains('user_id', $user->id) : false;
    }
}

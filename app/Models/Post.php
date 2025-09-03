<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','body','image_path'];

    // RELACIONES
    public function user()      { return $this->belongsTo(\App\Models\User::class); }
    public function comments()  { return $this->hasMany(\App\Models\Comment::class)->latest(); }
    public function likes()     { return $this->hasMany(\App\Models\Like::class); }
    public function images()    { return $this->hasMany(\App\Models\PostImage::class)->orderBy('position'); }

    // Accesor para @menciones y #hashtags
    public function getBodyHtmlAttribute(): string
    {
        $text = e($this->body);
        $text = nl2br($text);

        $text = preg_replace(
            '/(^|[^a-zA-Z0-9_])@([A-Za-z0-9_]{2,30})/',
            '$1<a href="'.url('/u/$2').'" class="text-blue-600 hover:underline">@$2</a>',
            $text
        );

        $text = preg_replace(
            '/(^|[^a-zA-Z0-9_])#([A-Za-z0-9_]{2,50})/',
            '$1<a href="'.url('/tag/$2').'" class="text-blue-600 hover:underline">#$2</a>',
            $text
        );

        return $text;
    }

    // Utilidad (opcional)
    public function isLikedBy(?\App\Models\User $user): bool
    {
        return $user ? $this->likes->contains('user_id', $user->id) : false;
    }
}

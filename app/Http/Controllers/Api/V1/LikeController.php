<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $r, Post $post)
    {
        $post->likes()->firstOrCreate(['user_id'=>$r->user()->id]);
        return ['liked'=>true,'likes'=>$post->likes()->count()];
    }

    public function destroy(Request $r, Post $post)
    {
        $post->likes()->where('user_id',$r->user()->id)->delete();
        return ['liked'=>false,'likes'=>$post->likes()->count()];
    }
}

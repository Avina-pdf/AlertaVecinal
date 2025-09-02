<?php

namespace App\Http\Controllers;

use App\Models\Post;

class FeedController extends Controller
{
    public function __construct()
    {
       
    }

    public function __invoke()
    {
        $posts = Post::with(['user','likes','comments.user'])
            ->latest()
            ->paginate(10); // o ->simplePaginate(10)

        return view('dashboard', compact('posts'));
    }
}

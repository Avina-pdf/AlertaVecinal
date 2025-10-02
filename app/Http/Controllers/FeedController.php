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
        $posts = Post::with(['user','images','likes','comments.user'])
            ->latest()
            ->paginate(10); // o ->simplePaginate(10)

        return view('dashboard', compact('posts'));
    }
    public function index()
{
    $posts = Post::with(['user', 'images'])
        ->latest()
        ->paginate(10);   // 👈 esto devuelve data+meta+links
    
    return response()->json($posts);
}

}

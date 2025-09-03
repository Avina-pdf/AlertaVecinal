<?php

namespace App\Http\Controllers;

use App\Models\Post;

class TagController extends Controller
{
    public function show(string $tag)
    {
        // Sencillo: buscar posts que contengan #tag (puedes cambiar a REGEXP si quieres más precisión)
        $posts = Post::with(['user','images','likes','comments.user'])
            ->where('body', 'like', '%#'.$tag.'%')
            ->latest()
            ->paginate(10);

        return view('tags.show', compact('posts','tag'));
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        return CommentResource::collection(
            $post->comments()->with('user:id,name')->latest()->paginate(20)
        );
    }

    public function store(Request $r, Post $post)
    {
        $data = $r->validate(['body'=>'required|string|max:1000']);
        $comment = $post->comments()->create([
            'user_id'=>$r->user()->id,
            'body'=>$data['body'],
        ]);
        return new CommentResource($comment->load('user:id,name'));
    }

    public function destroy(Request $r, Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();
        return ['message'=>'deleted'];
    }
}


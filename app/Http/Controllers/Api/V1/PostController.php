<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $r)
    {
        $q = Post::query()
            ->with(['user:id,name','images'])
            ->withCount(['likes','comments'])
            ->latest();

        $posts = $q->cursorPaginate(10);
        return PostResource::collection($posts)
            ->additional(['meta'=>['next_cursor'=>$posts->nextCursor()?->encode()]]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'body'     => ['required','string','max:2000'],
            'images'   => ['nullable','array','max:6'],
            'images.*' => ['image','max:5120'],
        ]);

        $post = $r->user()->posts()->create(['body'=>$data['body']]);

        if (!empty($data['images'])) {
            foreach ($data['images'] as $i => $file) {
                $path = $file->store('post-images','public');
                $post->images()->create(['path'=>$path,'position'=>$i]);
            }
        }

        return new PostResource(
            $post->load(['user:id,name','images'])->loadCount(['likes','comments'])
        );
    }

    public function show(Post $post)
    {
        $post->load(['user:id,name','images','comments.user:id,name'])
             ->loadCount(['likes','comments']);
        return new PostResource($post);
    }

    public function destroy(Request $r, Post $post)
    {
        $this->authorize('delete', $post);

        foreach ($post->images as $img) {
            Storage::disk('public')->delete($img->path);
        }
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();
        return response()->json(['message'=>'deleted']);
    }
}

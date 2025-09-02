<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller {
    public function store(Request $request){
        $validated = $request->validate([
            'body' => ['required','string','max:2000'],
            'image' => ['nullable','image','max:5120'] // 5MB
        ]);

        $path = null;
        if($request->hasFile('image')){
            $path = $request->file('image')->store('posts', 'public');
        }

        $request->user()->posts()->create([
            'body' => $validated['body'],
            'image_path' => $path,
        ]);

        return back()->with('status','Publicación creada');
    }

    public function destroy(Post $post){
        $this->authorize('delete', $post);
        if($post->image_path){
            Storage::disk('public')->delete($post->image_path);
        }
        $post->delete();
        return back();
    }
}

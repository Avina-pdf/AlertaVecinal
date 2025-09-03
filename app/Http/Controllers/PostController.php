<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Notifications\NewPostPublished;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request)
    {
        $validated = $request->validate([
            'body'     => ['required', 'string', 'max:2000'],
            'images'   => ['nullable', 'array', 'max:6'],
            'images.*' => ['image', 'max:5120'], // 5MB c/u
        ]);

        $post = $request->user()->posts()->create([
            'body' => $validated['body'],
        ]);

        // Guardar múltiples imágenes
        if (!empty($validated['images'])) {
            foreach ($validated['images'] as $i => $file) {
                $path = $file->store('post-images', 'public');
                $post->images()->create(['path' => $path, 'position' => $i]);
            }
        }

        // --- Enviar notificaciones (ANTES del redirect) ---
        $recipients = User::where('id', '!=', $request->user()->id)->get();

        if ($recipients->isEmpty()) {
            // Ambiente de pruebas con un solo usuario: notifícate a ti mismo para validar flujo
            $request->user()->notify(new NewPostPublished($post));
        } else {
            Notification::send($recipients, new NewPostPublished($post));
        }
        // ---------------------------------------------------

        return back()->with('status', 'Publicación creada');
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        $post->load('images');
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'body'            => ['required', 'string', 'max:2000'],
            'images'          => ['nullable', 'array', 'max:6'],
            'images.*'        => ['image', 'max:5120'],
            'remove_images'   => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
        ]);

        $post->update(['body' => $validated['body']]);

        // Borrar seleccionadas
        foreach ($request->input('remove_images', []) as $imgId) {
            $img = $post->images()->find($imgId);
            if ($img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }
        }

        // Agregar nuevas
        if (!empty($validated['images'])) {
            $start = (int) $post->images()->max('position') + 1;
            foreach ($validated['images'] as $i => $file) {
                $path = $file->store('post-images', 'public');
                $post->images()->create(['path' => $path, 'position' => $start + $i]);
            }
        }

        return redirect()->route('dashboard')->with('status', 'Publicación actualizada');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        foreach ($post->images as $img) {
            Storage::disk('public')->delete($img->path);
        }
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();
        return back();
    }
}

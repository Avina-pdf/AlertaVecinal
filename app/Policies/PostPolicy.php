<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function delete(User $user, Post $post): bool
    {
        // autor puede borrar; si tienes rol admin, puedes añadirlo aquí
       
         return $user->id === $post->user_id || $user->isAdmin();
        // o: return $post->user_id === $user->id || $user->is_admin;
    }

    public function update(User $user, Post $post): bool
    {
       
        return $user->id === $post->user_id || $user->isAdmin();
    }
}

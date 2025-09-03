<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewPostPublished extends Notification
{
    use Queueable;

    public function __construct(public Post $post) {}

    public function via($notifiable): array
    {
        return ['database']; // luego puedes agregar 'broadcast' o 'mail'
    }

    public function toDatabase($notifiable): array
    {
        return [
            'post_id'     => $this->post->id,
            'author_id'   => $this->post->user_id,
            'author_name' => optional($this->post->user)->name ?? 'Alguien',
            'excerpt'     => str($this->post->body)->limit(120),
            'created'     => $this->post->created_at?->toDateTimeString(),
        ];
    }

    // opcional, por si algún lugar usa toArray
    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}

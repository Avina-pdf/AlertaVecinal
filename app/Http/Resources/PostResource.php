<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        $authId = optional($request->user())->id;

        return [
            'id' => $this->id,
            'body' => $this->body,
            'body_html' => $this->body_html, // tu accessor con @ y #
            'author' => ['id'=>$this->user->id,'name'=>$this->user->name],
            'images' => $this->whenLoaded('images', fn() =>
                $this->images->map(fn($img)=> [
                    'id'=>$img->id,
                    'url'=> asset('storage/'.$img->path),
                    'position'=>$img->position
                ])
            ),
            'likes_count' => $this->likes_count ?? $this->likes()->count(),
            'comments_count' => $this->comments_count ?? $this->comments()->count(),
            'liked' => $authId ? $this->likes->contains('user_id',$authId) : false,
            'can' => [
                'update' => $request->user()?->can('update',$this->resource) ?? false,
                'delete' => $request->user()?->can('delete',$this->resource) ?? false,
            ],
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}

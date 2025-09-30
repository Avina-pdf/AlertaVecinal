<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'body'=>$this->body,
            'user'=>['id'=>$this->user->id,'name'=>$this->user->name],
            'can'=>['delete'=>$request->user()?->can('delete',$this->resource) ?? false],
            'created_at'=>$this->created_at?->toIso8601String(),
        ];
    }
}

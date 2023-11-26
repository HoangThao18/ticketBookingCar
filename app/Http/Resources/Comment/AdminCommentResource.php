<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'rate' => $this->rate,
            'user' => $this->user->name,
            'avatar' => $this->user->avatar,
            "car" => $this->car->name,
            'status' => $this->status,
            'content' => $this->content,
            'created_at' => $this->created_at,
        ];
    }
}

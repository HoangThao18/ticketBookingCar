<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommentResource extends JsonResource
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
            'user' => [
                "name" => $this->user->name,
                "avatar" => $this->user->avatar,
            ],
            'status' => $this->status,
            'content' => $this->content,
            'created_at' => $this->created_at,
        ];
    }
}

<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            "id" => $this->id,
            'rate' => $this->rate,
            'user' => $this->user->name,
            'avatar' => asset(Storage::url($this->user->avatar)),
            "car" => ["id" => $this->car->id, "name" => $this->car->name],
            'status' => $this->status,
            'content' => $this->content,
            'created_at' => $this->created_at,
        ];
    }
}

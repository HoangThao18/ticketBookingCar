<?php

namespace App\Http\Resources\News;

use App\Http\Resources\image\ImageResoure;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            "summary" => $this->summary,
            'content' => $this->content,
            'active' => $this->active,
            "view" => $this->view,
            "created_at" => $this->created_at,
            "imgs" => ImageResoure::collection($this->images),
        ];
    }
}

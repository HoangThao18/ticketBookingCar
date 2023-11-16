<?php

namespace App\Http\Resources\News;

use App\Http\Resources\image\ImageResoure;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class NewsResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'title' => $this->title,
            "summary" => $this->summary,
            'content' => $this->content,
            'active' => $this->active,
            "view" => $this->view,
            "created_at" => $this->created_at,
            "imgs" =>   !empty($this->img) ? asset(Storage::url($this->img)) : $this->url,
        ];
    }
}

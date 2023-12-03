<?php

namespace App\Http\Resources\TimePoint;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimePointsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->point->name,
            'address' => $this->point->address,
            'time' => $this->time,
            'type' => $this->type
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->station->name,
            'address' => $this->station->address,
            'time_in' => $this->time_in,
            'type' => $this->type,
            'time_out' => $this->time_out,
            'status' => $this->status,
        ];
    }
}

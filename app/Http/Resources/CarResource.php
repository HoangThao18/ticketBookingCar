<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'license_plate' => $this->license_plate,
            'type' => $this->type,
            'status' => $this->status,
            'number_seat' => $this->number_seat,
        ];
    }
}

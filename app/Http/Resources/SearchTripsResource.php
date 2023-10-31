<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchTripsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  $data = [
            'car' => ["name" => $this->car->name, "type" => $this->car->type],
            "departure_time" => $this->departure_time,
            "price" => $this->price,
            "available_seats" => $this->available_seats,
            "route" => new RouteResource($this->route),
        ];
    }
}

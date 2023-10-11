<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "route" => new RouteResource($this->route),
            'car' => new CarResource($this->car),
            'tickets' => TicketResource::collection($this->tickets->sortBy('seat.position', SORT_NATURAL))
        ];
    }
}

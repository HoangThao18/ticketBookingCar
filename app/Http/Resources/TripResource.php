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
        $data = [
            "driver" => new UserResource($this->driver),
            'car' => new CarResource($this->car),
            "departure_time" => $this->departure_time,
            "available_seats" => $this->available_seats,
            "route" => new RouteResource($this->route),
        ];

        if ($request->routeIs('trip.show')) {
            $data['tickets'] = TicketResource::collection($this->tickets->sortBy('seat.position', SORT_NATURAL));
        }

        return $data;
    }
}

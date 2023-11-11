<?php

namespace App\Http\Resources\Ticket;

use App\Http\Resources\TimePoint\TimePointsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketTripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'start_station' => $this->start,
            'end_station' => $this->end,
            'departure_time' => $this->departure_time,
            "schedule" => TimePointsResource::collection($this->time_points),
        ];
    }
}

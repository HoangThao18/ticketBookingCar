<?php

namespace App\Http\Resources\Trip;

use App\Http\Resources\Car\CarResource;
use App\Http\Resources\Seat\SeatResource;
use App\Http\Resources\TimePoint\TimePointsResource;
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
            'start_station' => $this->start,
            'end_station' => $this->end,
            "schedule" => TimePointsResource::collection($this->time_points),
            "departure_time" => $this->departure_time,
            'car' => new CarResource($this->car),
            'seats' => SeatResource::collection($this->seats)
        ];

        return $data;
    }
}

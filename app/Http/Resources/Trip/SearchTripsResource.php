<?php

namespace App\Http\Resources\Trip;

use App\Http\Resources\image\ImageResoure;
use App\Http\Resources\TimePoint\TimePointsResource;
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
        return   [
            'id' => $this->id,
            'start_station' => $this->start,
            'end_station' => $this->end,
            'car' => [
                "name" =>
                $this->car->name,
                "type" => $this->car->type,
            ],
            "departure_time" => $this->departure_time,
            "arrival_time" => $this->arrival_time,
            "price" => $this->price,
            "available_seats" => $this->available_seats,
            "schedule" => TimePointsResource::collection($this->time_points),
        ];
    }
}

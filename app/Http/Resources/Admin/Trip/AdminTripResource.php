<?php

namespace App\Http\Resources\Admin\Trip;

use App\Http\Resources\Admin\Car\AdminCarResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminTripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'start_station' => $this->start,
                'end_station' => $this->end,
                "departure_time" => $this->departure_time,
                "arrival_time" => $this->arrival_time,
                'status' => $this->status,
                "car" => $this->car->name,
                "driver" => $this->driver->name
            ];
    }
}

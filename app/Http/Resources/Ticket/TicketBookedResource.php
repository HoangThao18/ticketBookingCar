<?php

namespace App\Http\Resources\Ticket;

use App\Http\Resources\Car\CarResource;
use App\Http\Resources\TimePoint\TimePointsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketBookedResource extends JsonResource
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
            'user' => [
                "name" => $this->user->name,
                "email" => $this->user->email,
                "phone_number" => $this->user->phone_number,
            ],
            'car' => new CarResource($this->trip->car),
            'seat' => new TicketSeatResource($this->seat),
            'code' => $this->code,
            'status' => $this->status,
            "pickup_location" => $this->pickup_location,
            "dropoff_location" => $this->dropoff_location,
            'trip' => [
                'departure_time' => $this->trip->departure_time,
                'arrival_time' => $this->trip->departure_time,
                'status' => $this->trip->status,
                'start_location' => $this->trip->start,
                'end_location' => $this->trip->end,
                "schedule" => TimePointsResource::collection($this->trip->time_points->sortBy('time')),
            ],
        ];
    }
}

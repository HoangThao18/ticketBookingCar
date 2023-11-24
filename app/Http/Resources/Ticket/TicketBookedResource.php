<?php

namespace App\Http\Resources\Ticket;

use App\Http\Resources\Car\CarResource;
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
            'user' => [
                "name" => $this->user->name,
                "email" => $this->user->email,
                "phone_number" => $this->user->phone_number,
            ],
            'car' => new CarResource($this->trip->car),
            'seat' => new TicketSeatResource($this->seat),
            'code' => $this->code,
            "pickup_location" => $this->pickup_location,
            "dropoff_location" => $this->dropoff_location,
            'trip' => [
                'departure_time' => $this->departure_time,
                'arrival_time' => $this->departure_time,
                'start_location' => $this->trip->start,
                'end_location' => $this->trip->end,
            ],
        ];
    }
}

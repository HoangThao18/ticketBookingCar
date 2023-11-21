<?php

namespace App\Http\Resources\Ticket;


use App\Http\Resources\Ticket\SeatResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailTicketResource extends JsonResource
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
            'seat' => new TicketSeatResource($this->seat),
            'code' => $this->code,
            "pickup_location" => $this->pickup_location,
            "dropoff_location" => $this->dropoff_location,
            'trip' => new TicketTripResource($this->trip),
        ];
    }
}

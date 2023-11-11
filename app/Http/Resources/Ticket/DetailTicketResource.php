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
            'user' => new UserResource($this->user),
            'trip' => new TicketTripResource($this->trip),
            'seat' => new TicketSeatResource($this->seat),
            'status' => $this->status,
        ];
    }
}

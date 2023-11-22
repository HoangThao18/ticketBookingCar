<?php

namespace App\Http\Resources\Admin\Ticket;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "code" => $this->code,
            "price" => $this->price,
            "status" => $this->status,
            "user" => [
                "name" => $this->user->name,
                "phone_number" => $this->user->phone_number,
            ],
            "start_station" => $this->trip->start,
            "end_station" => $this->trip->end,
            "seat" => [
                'position' => $this->seat->position,
                'type' => $this->seat->type
            ]

        ];
    }
}

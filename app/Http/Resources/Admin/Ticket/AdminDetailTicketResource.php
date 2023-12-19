<?php

namespace App\Http\Resources\Admin\ticket;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AdminDetailTicketResource extends JsonResource
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
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone_numbere' => $this->user->phone_number,
            ],
            'car' => [
                "name" => $this->trip->car->name,
                'license_plate' => $this->trip->car->license_plate,
                'type' => $this->trip->car->type,
                'img' => asset(Storage::url($this->trip->car->primary_img)),
            ],
            "seat" => [
                'position' => $this->seat->position,
            ],
            "departure_time" => $this->trip->departure_time,
            "price" => $this->price,
            "status" => $this->status,
            "created_at" => $this->created_at,
            "payment_method" => $this->bill->payment_method,
            "pickup_point" => $this->pickup_location,
            "dropoff_point" => $this->dropoff_location,
        ];
    }
}

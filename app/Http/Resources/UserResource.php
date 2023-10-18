<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            "phone_number" => $this->phone_number,
            "address" => $this->address,
            "role" => $this->role,
            "avata" => $this->avata,
            "last_login_date" => $this->last_login_date,
        ];
    }
}
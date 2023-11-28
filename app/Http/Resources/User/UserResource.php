<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            "id" => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            "phone_number" => $this->phone_number,
            "address" => $this->address,
            "role" => $this->role,
            "avatar" =>   asset(Storage::url($this->avatar)),
            "last_login_date" => $this->last_login_date,
        ];
    }
}

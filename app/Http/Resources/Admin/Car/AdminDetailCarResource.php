<?php

namespace App\Http\Resources\Admin\Car;

use App\Http\Resources\Admin\Seat\AdminSeatResource;
use App\Http\Resources\Comment\AdminCommentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminDetailCarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'comment' =>  AdminCommentResource::collection($this->comments),
            'seats' => AdminSeatResource::collection($this->seats),
        ];
    }
}

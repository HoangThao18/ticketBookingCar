<?php

namespace App\Http\Resources\Admin\Timepoint;

use App\Http\Controllers\API\Admin\TripController;
use App\Http\Resources\Admin\Trip\AdminTripResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimePointResource extends JsonResource
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
            "time" => $this->time,
            "type" => $this->type,
            "point" => $this->point,
            "trip" => new AdminTripResource($this->trip),
            "create_at" => $this->created_at
        ];
    }
}

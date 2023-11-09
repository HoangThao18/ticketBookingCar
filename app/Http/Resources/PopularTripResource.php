<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PopularTripResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            [
                'start_location' => $this->start_station,
                'end_location' => $this->end_station,
                'price' => $this->min_price
            ]
        ];
    }
}

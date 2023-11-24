<?php

namespace App\Http\Resources\Car;

use App\Http\Resources\image\ImageResoure;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'license_plate' => $this->license_plate,
            'type' => $this->type,
            'status' => $this->status,
            'number_seat' => $this->number_seat,
            'primary_img' =>  asset(Storage::url($this->primary_img)),
            "sub_imgs" => ImageResoure::collection($this->images),
        ];
    }
}

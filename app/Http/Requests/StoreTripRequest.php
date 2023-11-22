<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'departure_time' => "required|date_format:Y-m-d H:i:s|after:now",
            'arrival_time' => "required|date_format:Y-m-d H:i:s|after:departure_time",
            "start_station" => ["required", Rule::exists('stations', 'id')],
            "end_station" => ["required", Rule::exists('stations', 'id')],
            "car_id" => ["required", Rule::exists('cars', 'id')],
            "status" => "nullable",
            "driver_id" => ["required", Rule::exists('users', 'id')->where('role', "driver")],
            "pickups" => "nullable|array",
            "dropoff" => "nullable|array",
        ];
    }
}

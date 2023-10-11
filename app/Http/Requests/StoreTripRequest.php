<?php

namespace App\Http\Requests;

use App\Models\Route;
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
            'departure_time' => "required",
            "arrival_time" => "required",
            "route_id" => ["required", Rule::exists('routes', 'id')],
            "car_id" => ["required", Rule::exists('cars', 'id')],
            "driver_id" => ["required", Rule::exists('users', 'id')->where('role', "driver")],
        ];
    }
}

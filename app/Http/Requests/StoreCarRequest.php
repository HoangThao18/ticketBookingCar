<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarRequest extends FormRequest
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
            'name' => "required",
            'license_plate' => "required|unique:cars",
            'type' => "required",
            "img" => "nullable",
            "seats" => "required|array",
            "seats.*.position" => "required",
            "seats.*.type" => "required",
            "seats.*.price" => "required|numeric",
        ];
    }
}

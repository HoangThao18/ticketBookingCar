<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            "email" => "required|email|unique:users",
            "password" => "required|max:30|min:8",
            "name" => "required",
            'password_confirm' => "required|same:password",
            'phone_number' => "required|min:10|unique:users"
        ];
    }
    public function messages(): array
    {
        return [
            'phone_number.unique' => 'Số điện thoại đã tồn tại',
        ];
    }
}

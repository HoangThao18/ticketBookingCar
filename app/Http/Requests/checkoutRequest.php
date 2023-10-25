<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;

class checkoutRequest extends FormRequest
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
            'amount' => "required|numeric",
            'bankc_code' => "required|string",
            "tickets" => ['required'],
            'tickets.*' => [
                'required',
                function ($artribute, $value, $fail) {
                    if ($value) {
                        $ticket = Ticket::find($value);
                        if (!$ticket) {
                            $fail("The {$artribute} is not exists.");
                        } else {
                            $seat_position = $ticket->seat->position;
                            if ($ticket->status !== "còn trống") {
                                $fail("seat position {$seat_position} is invalid");
                            }
                        }
                    }
                }
            ]
        ];
    }
}

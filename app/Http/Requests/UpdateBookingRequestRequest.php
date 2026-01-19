<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:accepted,declined'],
            'photographer_response' => ['nullable', 'string', 'max:1000'],
            'proposed_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Veuillez accepter ou refuser la demande.',
            'status.in' => 'Le statut n\'est pas valide.',
            'photographer_response.max' => 'La réponse ne peut pas dépasser 1000 caractères.',
            'proposed_price.numeric' => 'Le prix proposé doit être un nombre.',
        ];
    }
}

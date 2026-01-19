<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date', 'after_or_equal:today'],
            'is_available' => ['boolean'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'La date est requise.',
            'date.date' => 'La date n\'est pas valide.',
            'date.after_or_equal' => 'La date doit être aujourd\'hui ou dans le futur.',
            'note.max' => 'La note ne peut pas dépasser 255 caractères.',
        ];
    }
}

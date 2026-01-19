<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkUpdateAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dates' => ['required', 'array', 'min:1'],
            'dates.*' => ['date', 'after_or_equal:today'],
            'is_available' => ['required', 'boolean'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'dates.required' => 'Veuillez sélectionner au moins une date.',
            'dates.*.date' => 'Une des dates sélectionnées n\'est pas valide.',
            'is_available.required' => 'Veuillez indiquer la disponibilité.',
        ];
    }
}

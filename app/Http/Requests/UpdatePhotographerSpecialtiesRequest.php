<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhotographerSpecialtiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'specialties' => ['required', 'array', 'min:1'],
            'specialties.*.id' => ['required', 'exists:specialties,id'],
            'specialties.*.level' => ['required', 'in:beginner,intermediate,expert'],
        ];
    }

    public function messages(): array
    {
        return [
            'specialties.required' => 'Veuillez sélectionner au moins une spécialité.',
            'specialties.min' => 'Veuillez sélectionner au moins une spécialité.',
            'specialties.*.id.exists' => 'La spécialité sélectionnée n\'existe pas.',
            'specialties.*.level.in' => 'Le niveau d\'expérience n\'est pas valide.',
        ];
    }
}

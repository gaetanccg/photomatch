<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhotographerProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bio' => ['required', 'string', 'max:1000'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:50'],
            'hourly_rate' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'daily_rate' => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'bio.required' => 'La biographie est requise.',
            'bio.max' => 'La biographie ne peut pas dépasser 1000 caractères.',
            'keywords.max' => 'Les mots-clés ne peuvent pas dépasser 500 caractères.',
            'experience_years.required' => 'Les années d\'expérience sont requises.',
            'experience_years.integer' => 'Les années d\'expérience doivent être un nombre entier.',
            'hourly_rate.required' => 'Le tarif horaire est requis.',
            'hourly_rate.numeric' => 'Le tarif horaire doit être un nombre.',
            'daily_rate.required' => 'Le tarif journalier est requis.',
            'daily_rate.numeric' => 'Le tarif journalier doit être un nombre.',
            'portfolio_url.url' => 'L\'URL du portfolio doit être une URL valide.',
        ];
    }
}

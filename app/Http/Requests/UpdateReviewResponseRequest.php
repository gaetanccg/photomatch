<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewResponseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isPhotographer();
    }

    public function rules(): array
    {
        return [
            'photographer_response' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'photographer_response.required' => 'Veuillez saisir votre réponse.',
            'photographer_response.max' => 'La réponse ne doit pas dépasser 1000 caractères.',
        ];
    }

    public function attributes(): array
    {
        return [
            'photographer_response' => 'réponse',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isClient();
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Veuillez attribuer une note.',
            'rating.integer' => 'La note doit être un nombre entier.',
            'rating.min' => 'La note minimum est de 1 étoile.',
            'rating.max' => 'La note maximum est de 5 étoiles.',
            'comment.max' => 'Le commentaire ne doit pas dépasser 2000 caractères.',
        ];
    }

    public function attributes(): array
    {
        return [
            'rating' => 'note',
            'comment' => 'commentaire',
        ];
    }
}

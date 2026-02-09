<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhotographerTagsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tags' => ['nullable', 'array', 'max:15'],
            'tags.*' => ['required', 'string', 'min:2', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'tags.max' => 'Vous ne pouvez pas avoir plus de 15 tags.',
            'tags.*.min' => 'Chaque tag doit contenir au moins 2 caractères.',
            'tags.*.max' => 'Chaque tag ne peut pas dépasser 50 caractères.',
        ];
    }
}

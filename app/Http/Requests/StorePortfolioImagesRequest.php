<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePortfolioImagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->photographer !== null;
    }

    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'max:10'],
            'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => 'Veuillez sélectionner au moins une image.',
            'images.max' => 'Vous pouvez uploader maximum 10 images à la fois.',
            'images.*.image' => 'Le fichier doit être une image.',
            'images.*.mimes' => 'Format accepté : JPEG, PNG, JPG, WEBP.',
            'images.*.max' => 'Chaque image ne doit pas dépasser 5 Mo.',
        ];
    }
}

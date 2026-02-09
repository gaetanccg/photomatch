<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderPortfolioImagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->photographer !== null;
    }

    public function rules(): array
    {
        return [
            'order' => ['required', 'array'],
            'order.*' => ['required', 'integer', 'exists:portfolio_images,id'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Models\PortfolioImage;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePortfolioImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $portfolioImage = $this->route('portfolioImage');

        return auth()->check()
            && $portfolioImage instanceof PortfolioImage
            && $portfolioImage->photographer_id === auth()->user()->photographer?->id;
    }

    public function rules(): array
    {
        return [
            'caption' => ['nullable', 'string', 'max:255'],
            'specialty_id' => ['nullable', 'exists:specialties,id'],
            'is_featured' => ['boolean'],
        ];
    }
}

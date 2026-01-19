<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isClient();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_id' => ['required', 'exists:photo_projects,id'],
            'photographer_id' => ['required', 'exists:photographers,id'],
            'message' => ['nullable', 'string', 'max:2000'],
            'proposed_rate' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'project_id.required' => 'Veuillez sélectionner un projet.',
            'project_id.exists' => 'Le projet sélectionné n\'existe pas.',
            'photographer_id.required' => 'Le photographe est requis.',
            'photographer_id.exists' => 'Le photographe sélectionné n\'existe pas.',
            'message.max' => 'Le message ne peut pas dépasser 2000 caractères.',
            'proposed_rate.numeric' => 'Le tarif proposé doit être un nombre.',
            'proposed_rate.min' => 'Le tarif proposé ne peut pas être négatif.',
        ];
    }
}

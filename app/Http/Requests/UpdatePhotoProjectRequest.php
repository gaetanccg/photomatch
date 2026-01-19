<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhotoProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $project = $this->route('project');

        return $this->user()->isClient()
            && $this->user()->id === $project->client_id
            && in_array($project->status, ['draft', 'published']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'project_type' => ['required', 'in:event,product,real_estate,corporate,portrait,other'],
            'event_date' => ['required_if:project_type,event', 'nullable', 'date', 'after:today'],
            'date_start' => ['nullable', 'date', 'after_or_equal:today'],
            'date_end' => ['nullable', 'date', 'after:date_start'],
            'location' => ['required', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0', 'gte:budget_min'],
            'estimated_duration' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', 'in:draft,published'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre du projet est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'description.required' => 'La description du projet est obligatoire.',
            'description.max' => 'La description ne peut pas dépasser 2000 caractères.',
            'project_type.required' => 'Le type de projet est obligatoire.',
            'project_type.in' => 'Le type de projet sélectionné n\'est pas valide.',
            'event_date.required_if' => 'La date de l\'événement est obligatoire pour ce type de projet.',
            'event_date.date' => 'La date de l\'événement n\'est pas valide.',
            'event_date.after' => 'La date de l\'événement doit être dans le futur.',
            'date_start.date' => 'La date de début n\'est pas valide.',
            'date_start.after_or_equal' => 'La date de début doit être aujourd\'hui ou dans le futur.',
            'date_end.date' => 'La date de fin n\'est pas valide.',
            'date_end.after' => 'La date de fin doit être après la date de début.',
            'location.required' => 'La localisation est obligatoire.',
            'location.max' => 'La localisation ne peut pas dépasser 500 caractères.',
            'latitude.numeric' => 'La latitude doit être un nombre.',
            'latitude.between' => 'La latitude doit être comprise entre -90 et 90.',
            'longitude.numeric' => 'La longitude doit être un nombre.',
            'longitude.between' => 'La longitude doit être comprise entre -180 et 180.',
            'budget_min.numeric' => 'Le budget minimum doit être un nombre.',
            'budget_min.min' => 'Le budget minimum ne peut pas être négatif.',
            'budget_max.numeric' => 'Le budget maximum doit être un nombre.',
            'budget_max.min' => 'Le budget maximum ne peut pas être négatif.',
            'budget_max.gte' => 'Le budget maximum doit être supérieur ou égal au budget minimum.',
            'estimated_duration.integer' => 'La durée estimée doit être un nombre entier.',
            'estimated_duration.min' => 'La durée estimée doit être d\'au moins 1 heure.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'title' => 'titre',
            'description' => 'description',
            'project_type' => 'type de projet',
            'event_date' => 'date de l\'événement',
            'date_start' => 'date de début',
            'date_end' => 'date de fin',
            'location' => 'localisation',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'budget_min' => 'budget minimum',
            'budget_max' => 'budget maximum',
            'estimated_duration' => 'durée estimée',
            'status' => 'statut',
        ];
    }
}

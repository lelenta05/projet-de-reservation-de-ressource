<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRessourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //les regles de validation pour la function update dans le controller Ressource 
        //sommetimes :Laravel ignore la règle si le champ est absent (pas d'erreur de validation ), mais valide si le champ est présent.
        return [
            'nom'=> 'sometimes|required|string |max:255',
            'type' => 'sometimes|required|string|max:100',
            'localisation' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'capacite' => 'sometimes|required|integer|min:1',
        ];
    }
}

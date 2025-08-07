<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRessourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // L'autorisation se fait via les policies, donc on laisse true ici.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //les regles de validation dans la function store du controller Ressource 
        return [
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'localisation' => 'required|string|max:255',
            'description' => 'required|string',
            'capacite' => 'required|integer|min:1',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeficiencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $deficiency = $this->route('deficiency');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('deficiencies', 'name')->ignore($deficiency?->id),
            ],
            'cid_code' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('deficiencies', 'cid_code')->ignore($deficiency?->id),
            ],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome da deficiência é obrigatório.',
            'name.unique' => 'Esta deficiência já está cadastrada no sistema.',
            'cid_code.unique' => 'Este código CID já está vinculado a outra deficiência.',
            'description.max' => 'A descrição não pode ultrapassar 1000 caracteres.',
        ];
    }
}

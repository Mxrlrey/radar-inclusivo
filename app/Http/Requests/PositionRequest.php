<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $position = $this->route('position');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                /* Garante que o nome do cargo seja único,
                   mas ignora o próprio registro se for uma edição. */
                Rule::unique('positions', 'name')->ignore($position?->id),
            ],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean',
            /* Mantemos a validação de permissões caso você
               utilize o sistema de ACL no seu Radar. */
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }

    protected function prepareForValidation(): void
    {
        /* Garante integridade para campos vindos de checkbox. */
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do cargo é obrigatório.',
            'name.unique' => 'Este cargo já está cadastrado.',
            'name.max' => 'O nome não pode ultrapassar 255 caracteres.',
            'description.max' => 'A descrição não pode ultrapassar 1000 caracteres.',
            'is_active.boolean' => 'O status de ativação deve ser verdadeiro ou falso.',
            'permissions.*.exists' => 'Uma ou mais permissões selecionadas são inválidas.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $student = $this->route('student');

        return [
            // PERSON
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],

            'document' => [
                'required',
                'string',
                'max:20',
                Rule::unique('people', 'document')
                    ->ignore($student?->person_id ?? null, 'id')
            ],

            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],

            // STUDENT
            'registration' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students', 'registration')
                    ->ignore($student?->id)
            ],

            'entry_date' => ['required', 'date'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'document' => preg_replace('/\D/', '', $this->document),
        ]);
    }

    public function messages(): array
    {
        return [
            // PERSON
            'name.required' => 'O nome do aluno é obrigatório.',
            'name.max' => 'O nome não pode ultrapassar 255 caracteres.',

            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',

            'document.required' => 'O CPF é obrigatório.',
            'document.unique' => 'Já existe uma pessoa cadastrada com este CPF.',

            'birth_date.date' => 'A data de nascimento deve ser uma data válida.',

            // STUDENT
            'registration.required' => 'A matrícula é obrigatória.',
            'registration.unique' => 'Já existe um aluno com esta matrícula.',

            'entry_date.required' => 'A data de ingresso é obrigatória.',
            'entry_date.date' => 'A data de ingresso deve ser válida.',
        ];
    }
}

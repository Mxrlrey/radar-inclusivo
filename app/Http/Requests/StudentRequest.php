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
            'person_id' => ['required', 'exists:people,id'],
            'registration' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students')->ignore($student?->id)
            ],
            'entry_date' => ['required', 'date'],
            'is_active' => ['sometimes', 'boolean'],
            'deficiencies' => ['nullable', 'array'],
            'deficiencies.*' => ['exists:deficiencies,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}

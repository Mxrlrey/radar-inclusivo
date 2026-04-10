<?php

namespace App\Http\Requests;

use App\Models\Person;
use App\Rules\Cpf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Garante que apenas usuários autenticados e com vínculo de profissional acessem
        $user = auth()->user();
        return $user && $user->professional_id;
    }

    public function rules(): array
    {
        $user = auth()->user();
        $personId = $user->professional?->person_id;

        return [
            'name'       => ['required', 'string', 'max:255'],
            'document'   => [
                'required',
                'string',
                new Cpf,
                Rule::unique('people', 'document')->ignore($personId),
            ],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender'     => ['required', Rule::in(array_keys(Person::genderOptions()))],
            'email'      => [
                'required',
                'email',
                Rule::unique('people', 'email')->ignore($personId)
            ],
            'phone'      => ['nullable', 'string', 'max:20'],
            'address'    => ['nullable', 'string', 'max:500'],
            'photo'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],

            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)->letters()->numbers()
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'document'     => $this->document ? preg_replace('/[^0-9]/', '', $this->document) : null,
            'phone'        => $this->phone ? preg_replace('/[^0-9]/', '', $this->phone) : null,
            'remove_photo' => $this->boolean('remove_photo'),
        ]);
    }

    public function messages(): array
    {
        return [
            'document.unique' => 'Este CPF já está em uso.',
            'email.unique' => 'Este e-mail já pertence a outra conta.',
            'password.confirmed' => 'As senhas digitadas não conferem.',
            'password.min' => 'A nova senha deve ter pelo menos 8 caracteres com letras e números.',
        ];
    }
}

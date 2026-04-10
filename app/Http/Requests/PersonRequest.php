<?php

namespace App\Http\Requests;

use App\Enums\Gender;
use App\Rules\Cpf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// Importante: importe o seu Enum aqui

class PersonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $person = $this->route('person');

        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'document' => [
                'required',
                'string',
                new Cpf,
                Rule::unique('people', 'document')->ignore($person?->id)
            ],
            'birth_date' => ['required', 'date', 'before_or_equal:today'],
            'gender' => [
                'required',
                Rule::enum(Gender::class)
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('people', 'email')->ignore($person?->id)
            ],
            'phone'        => ['nullable', 'string', 'max:20'],
            'address'      => ['nullable', 'string', 'max:500'],
            'photo'        => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'remove_photo' => ['sometimes', 'boolean'],
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
            'name.required'     => 'O nome completo é obrigatório.',
            'document.required' => 'O CPF é obrigatório.',
            'document.unique'   => 'Este CPF já está cadastrado.',
            'gender.required'   => 'O campo gênero é obrigatório.',
            'gender.Illuminate\Validation\Rules\Enum' => 'O gênero selecionado é inválido.',
            'email.unique'      => 'Este e-mail já está sendo utilizado.',
            'photo.max'         => 'A foto não pode ultrapassar 2MB.',
        ];
    }
}

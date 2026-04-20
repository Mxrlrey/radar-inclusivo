<?php

namespace App\Http\Requests;

use App\Enums\Gender; // Importando o seu novo Enum
use App\Rules\Cpf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfessionalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $professional = $this->route('professional');
        $personId = $professional?->person_id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'document' => [
                'required',
                'string',
                new Cpf,
                Rule::unique('people', 'document')->ignore($personId)
            ],
            'birth_date' => ['required', 'date', 'before_or_equal:today'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'email' => [
                'required',
                'email',
                Rule::unique('people', 'email')->ignore($personId)
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'registration' => [
                'required',
                'string',
                'max:50',
                Rule::unique('professionals', 'registration')->ignore($professional?->id)
            ],
            'position_id' => ['required', 'exists:positions,id'],
            'entry_date' => ['required', 'date'],
            'is_active' => ['sometimes', 'boolean'],
            'remove_photo' => ['sometimes', 'boolean'],
            'is_admin' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'document' => $this->document ? preg_replace('/[^0-9]/', '', $this->document) : null,
            'phone' => $this->phone ? preg_replace('/[^0-9]/', '', $this->phone) : null,
            'remove_photo' => $this->boolean('remove_photo'),
            'is_admin' => $this->boolean('is_admin'),
            'is_active' => $this->has('is_active') ? $this->boolean('is_active') : true,
        ]);
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'Informe o nome do profissional.',
            'document.required'     => 'Informe o CPF do profissional.',
            'document.unique'       => 'Este CPF já está cadastrado no sistema.',
            'birth_date.required'   => 'Informe a data de nascimento.',
            'birth_date.before_or_equal' => 'A data de nascimento não pode ser no futuro.',
            'gender.required'       => 'Selecione o gênero.',
            'email.required'        => 'Informe o e-mail do profissional.',
            'email.email'           => 'Informe um e-mail válido.',
            'email.unique'          => 'Este e-mail já está cadastrado no sistema.',
            'registration.required' => 'Informe a matrícula do profissional.',
            'registration.unique'   => 'Esta matrícula já está em uso.',
            'position_id.required'  => 'Selecione o cargo do profissional.',
            'entry_date.required'   => 'A data de ingresso é obrigatória.',
            'photo.image'           => 'O arquivo deve ser uma imagem.',
            'photo.max'             => 'A foto não pode ser maior que 2MB.',
        ];
    }
}

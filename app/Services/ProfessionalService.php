<?php

namespace App\Services;

use App\Models\Person;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfessionalService
{
    /**
     * RF: cria um profissional com pessoa e usuário vinculados em transação única.
     * Uso: cadastro de profissionais com acesso e vínculo funcional no sistema.
     */
    public function store(array $data): Professional
    {
        return DB::transaction(fn() => $this->persist(new Professional(), $data));
    }

    /**
     * RF: atualiza um profissional reutilizando a rotina central de persistência.
     * Uso: manutenção de dados pessoais, funcionais e de acesso do profissional.
     */
    public function update(Professional $professional, array $data): Professional
    {
        return DB::transaction(fn() => $this->persist($professional, $data));
    }

    /**
     * RF: remove um profissional e seus vínculos derivados com limpeza de mídia.
     * Uso: exclusão administrativa de profissionais desligados ou cadastrados indevidamente.
     */
    public function delete(Professional $professional): void
    {
        DB::transaction(function () use ($professional) {
            $person = $professional->person;

            if ($person && $person->photo) {
                Storage::disk('public')->delete($person->photo);
            }

            $professional->user()?->delete();
            $professional->delete();
            $person?->delete();
        });
    }

    /**
     * RF: centraliza a persistência do profissional, da pessoa e do usuário vinculado.
     * Uso: unifica os fluxos de criação e edição do cadastro profissional.
     */
    protected function persist(Professional $professional, array $data): Professional
    {
        $person = $this->savePerson($professional, $data);

        $this->saveProfessional($professional, $person, $data);

        $this->syncUser($professional, $person, $data);

        return $professional->load('person', 'position', 'user');
    }

    /**
     * RF: salva a pessoa vinculada ao profissional com tratamento de foto.
     * Uso: garante consistência dos dados pessoais antes de persistir o profissional.
     */
    private function savePerson(Professional $professional, array $data): Person
    {
        $person = $professional->person ?? new Person();

        if (!empty($data['remove_photo']) || isset($data['photo'])) {
            if ($person->photo) {
                Storage::disk('public')->delete($person->photo);
            }
            $data['photo'] = isset($data['photo'])
                ? $data['photo']->store('photos/professionals', 'public')
                : null;
        }

        $person->fill($data)->save();
        return $person;
    }

    /**
     * RF: salva os dados específicos do profissional com defaults de ingresso.
     * Uso: persiste o vínculo funcional após a gravação da pessoa.
     */
    private function saveProfessional(Professional $professional, Person $person, array $data): void
    {
        $data['person_id'] = $person->id;

        if (!$professional->exists && empty($data['entry_date'])) {
            $data['entry_date'] = now();
        }

        $professional->fill($data)->save();
    }

    /**
     * RF: sincroniza o usuário de acesso associado ao profissional.
     * Uso: mantém credenciais e flags administrativas alinhadas ao cadastro funcional.
     */
    private function syncUser(Professional $professional, Person $person, array $data): void
    {
        $user = $professional->user ?? new User();

        $userData = [
            'name'            => $person->name,
            'email'           => $person->email,
            'professional_id' => $professional->id,
            'is_admin'        => (bool) ($data['is_admin'] ?? ($user->is_admin ?? false)),
            'is_active'       => (bool) ($data['is_active'] ?? ($user->is_active ?? true)),
        ];

        if (!$user->exists) {
            $user->password = Hash::make('napne2026');
        }

        $user->fill($userData)->save();
    }
}

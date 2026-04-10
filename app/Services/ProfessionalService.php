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
    public function store(array $data): Professional
    {
        return DB::transaction(fn() => $this->persist(new Professional(), $data));
    }

    public function update(Professional $professional, array $data): Professional
    {
        return DB::transaction(fn() => $this->persist($professional, $data));
    }

    public function delete(Professional $professional): void
    {
        DB::transaction(function () use ($professional) {
            $person = $professional->person;

            if ($person && $person->photo) {
                Storage::disk('public')->delete($person->photo);
            }

            // O User e o Professional serão excluídos (se houver Cascade no banco)
            // ou deletados manualmente aqui para garantir integridade.
            $professional->user()?->delete();
            $professional->delete();
            $person?->delete();
        });
    }

    protected function persist(Professional $professional, array $data): Professional
    {
        $person = $this->savePerson($professional, $data);

        $this->saveProfessional($professional, $person, $data);

        $this->syncUser($professional, $person, $data);

        return $professional->load('person', 'position', 'user');
    }

    private function savePerson(Professional $professional, array $data): Person
    {
        $person = $professional->person ?? new Person();

        // Gerenciamento de Foto
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

    private function saveProfessional(Professional $professional, Person $person, array $data): void
    {
        $data['person_id'] = $person->id;

        // Se for criação e não veio entry_date, assume hoje
        if (!$professional->exists && empty($data['entry_date'])) {
            $data['entry_date'] = now();
        }

        $professional->fill($data)->save();
    }

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

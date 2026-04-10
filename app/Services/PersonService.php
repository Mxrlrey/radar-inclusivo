<?php

namespace App\Services;

use App\Models\Person;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PersonService
{
    public function store(array $data): Person
    {
        return DB::transaction(fn() => $this->persist(new Person(), $data));
    }

    public function update(Person $person, array $data): Person
    {
        return DB::transaction(fn() => $this->persist($person, $data));
    }

    public function delete(Person $person): void
    {
        DB::transaction(function () use ($person) {
            if ($person->photo) {
                Storage::disk('public')->delete($person->photo);
            }
            $person->delete();
        });
    }

    /**
     * Lógica central de persistência baseada no padrão Inclusive Radar
     */
    protected function persist(Person $person, array $data): Person
    {
        $this->handlePhoto($person, $data);

        $person->fill($data)->save();

        return $person;
    }

    /**
     * Gerencia o upload e a exclusão física de fotos
     */
    private function handlePhoto(Person $person, array &$data): void
    {
        // Se solicitou remoção ou enviou uma nova foto
        if (!empty($data['remove_photo']) || isset($data['photo'])) {
            if ($person->photo) {
                Storage::disk('public')->delete($person->photo);
            }

            $data['photo'] = isset($data['photo'])
                ? $data['photo']->store('photos/people', 'public')
                : null;
        } else {
            // Se nada foi enviado, mantém o que já está no banco
            unset($data['photo']);
        }
    }
}

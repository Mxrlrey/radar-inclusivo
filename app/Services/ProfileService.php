<?php

namespace App\Services;

use App\Models\User;
use App\Exceptions\BusinessRuleException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    /**
     * RF: atualiza o perfil do usuário autenticado e sua pessoa vinculada em transação única.
     * Uso: edição de dados pessoais, foto e credenciais no módulo de perfil.
     */
    public function updateProfile(User $user, array $data): void
    {
        DB::transaction(function () use ($user, $data) {
            $person = $user->professional?->person;

            if (!$person) {
                throw new BusinessRuleException("Vínculo de pessoa não encontrado para o seu usuário.");
            }

            if (!empty($data['remove_photo']) || isset($data['photo'])) {
                if ($person->photo) {
                    Storage::disk('public')->delete($person->photo);
                }

                $data['photo'] = isset($data['photo'])
                    ? $data['photo']->store('photos/profiles', 'public')
                    : null;
            }

            $person->fill($data)->save();

            $userData = [
                'name'  => $data['name'],
                'email' => $data['email'],
            ];

            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            $user->update($userData);
        });
    }
}

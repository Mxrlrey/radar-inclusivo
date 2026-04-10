<?php

namespace App\Services;

use App\Models\User;
use App\Exceptions\BusinessRuleException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function updateProfile(User $user, array $data): void
    {
        DB::transaction(function () use ($user, $data) {
            // 1. Identifica a Person vinculada
            $person = $user->professional?->person;

            if (!$person) {
                throw new BusinessRuleException("Vínculo de pessoa não encontrado para o seu usuário.");
            }

            // 2. Gerenciamento de Foto (Upload/Remoção)
            if (!empty($data['remove_photo']) || isset($data['photo'])) {
                if ($person->photo) {
                    Storage::disk('public')->delete($person->photo);
                }

                $data['photo'] = isset($data['photo'])
                    ? $data['photo']->store('photos/profiles', 'public')
                    : null;
            }

            // 3. Persistência dos Dados
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

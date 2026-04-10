<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private ProfileService $service
    ) {}

    public function edit(): View|RedirectResponse
    {
        $user = auth()->user();

        // Regra: Admins puros não editam perfil de 'Person' (tabela people)
        if ($user->isAdmin() && !$user->professional_id) {
            return redirect()->route('dashboard')
                ->with('error', 'Administradores globais não possuem perfil de dados pessoais vinculado.');
        }

        $person = $user->professional->person;
        $professional = $user->professional;

        return view('pages.profile.edit', compact('person', 'professional'));
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        $this->service->updateProfile(
            auth()->user(),
            $request->validated()
        );

        return back()->with('success', 'Seu perfil foi atualizado com sucesso!');
    }
}

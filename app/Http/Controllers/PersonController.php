<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Http\Requests\PersonRequest;
use App\Models\Person;
use App\Services\PersonService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PersonController extends Controller
{
    public function __construct(
        private PersonService $service
    ) {}

    public function index(Request $request): View
    {
        $people = Person::query()
            ->name($request->name)
            ->document($request->document)
            ->email($request->email)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        if ($request->ajax()) {
            return view(
                'pages.people.partials.table',
                compact('people')
            );
        }

        return view(
            'pages.people.index',
            compact('people')
        );
    }

    public function create(): View
    {
        return view(
            'pages.people.create',
            $this->formData()
        );
    }

    public function store(PersonRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()
            ->route('pessoas.index')
            ->with('success', 'Pessoa cadastrada com sucesso!');
    }

    public function edit(Person $person): View
    {
        return view(
            'pages.people.edit',
            $this->formData() + compact('person')
        );
    }

    public function update(PersonRequest $request, Person $person): RedirectResponse
    {
        $this->service->update($person, $request->validated());

        return redirect()
            ->route('pessoas.index')
            ->with('success', 'Cadastro atualizado com sucesso!');
    }

    public function destroy(Person $person): RedirectResponse
    {
        $this->service->delete($person);

        return redirect()
            ->route('pessoas.index')
            ->with('success', 'Registro removido com sucesso!');
    }

    /**
     * Centraliza os dados necessários para os formulários de Pessoa
     */
    private function formData(): array
    {
        return [
            'genderOptions' => Gender::options(),
        ];
    }
}

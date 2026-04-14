<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Http\Requests\ProfessionalRequest;
use App\Models\Person;
use App\Models\Position;
use App\Models\Professional;
use App\Services\ProfessionalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfessionalController extends Controller
{
    public function __construct(
        private ProfessionalService $service
    ) {}

    public function index(Request $request): View
    {
        $professionals = Professional::with(['person', 'position'])
            ->name($request->name)
            ->email($request->email)
            ->position($request->position_id)
            ->active($request->is_active)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $data = $this->formData();

        if ($request->ajax()) {
            return view(
                'pages.professionals.partials.table',
                $data + compact('professionals')
            );
        }

        return view(
            'pages.professionals.index',
            $data + compact('professionals')
        );
    }

    public function create(): View
    {
        return view(
            'pages.professionals.create',
            $this->formData()
        );
    }

    public function store(ProfessionalRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()
            ->route('professionals.index')
            ->with('success', 'Profissional cadastrado com sucesso!');
    }

    public function show(Professional $professional): View
    {
        $professional->load(['person', 'position', 'user']);

        return view('pages.professionals.show', compact('professional'));
    }

    public function edit(Professional $professional): View
    {
        $professional->load(['person', 'user']);

        return view(
            'pages.professionals.edit',
            $this->formData() + ['professional' => $professional]
        );
    }

    public function update(ProfessionalRequest $request, Professional $professional): RedirectResponse
    {
        $this->service->update($professional, $request->validated());

        return redirect()
            ->route('professionals.index')
            ->with('success', 'Profissional atualizado com sucesso!');
    }

    public function destroy(Professional $professional): RedirectResponse
    {
        $this->service->delete($professional);

        return redirect()
            ->route('professionals.index')
            ->with('success', 'Profissional removido com sucesso!');
    }

    private function formData(): array
    {
        return [
            'positions' => Position::where('is_active', true)->orderBy('name')->get(),
            'genders' => Gender::options(),
        ];
    }
}

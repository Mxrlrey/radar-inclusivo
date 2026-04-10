<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeficiencyRequest;
use App\Models\Deficiency;
use App\Services\DeficiencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeficiencyController extends Controller
{
    public function __construct(
        private DeficiencyService $service
    ) {}

    public function index(Request $request): View
    {
        $deficiencies = Deficiency::query()
            ->name($request->name)
            ->cid($request->cid_code)
            ->active($request->is_active)
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        if ($request->ajax()) {
            return view(
                'pages.deficiencies.partials.table',
                compact('deficiencies')
            );
        }

        return view(
            'pages.deficiencies.index',
            compact('deficiencies')
        );
    }

    public function create(): View
    {
        return view('pages.deficiencies.create',);
    }

    public function store(DeficiencyRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()
            ->route('deficiencies.index')
            ->with('success', 'Deficiência cadastrada com sucesso!');
    }

    public function show(Deficiency $deficiency): View
    {
        $deficiency->loadCount('students');
        return view('pages.deficiencies.show', compact('deficiency'));
    }

    public function edit(Deficiency $deficiency): View
    {
        return view('pages.deficiencies.edit', compact('deficiency'));
    }

    public function update(DeficiencyRequest $request, Deficiency $deficiency): RedirectResponse
    {
        $this->service->update($deficiency, $request->validated());

        return redirect()
            ->route('deficiencies.index')
            ->with('success', 'Deficiência atualizada com sucesso!');
    }

    public function toggleActive(Deficiency $deficiency): RedirectResponse
    {
        $this->service->update($deficiency, ['is_active' => !$deficiency->is_active]);

        $status = $deficiency->is_active ? 'ativada' : 'desativada';

        return redirect()
            ->route('deficiencies.index')
            ->with('success', "Deficiência {$status} com sucesso!");
    }

    public function destroy(Deficiency $deficiency): RedirectResponse
    {
        $this->service->delete($deficiency);

        return redirect()
            ->route('deficiencies.index')
            ->with('success', 'Deficiência removida com sucesso!');

    }
}

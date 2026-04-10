<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessibilityFeatureRequest;
use App\Models\AccessibilityFeature;
use App\Services\AccessibilityFeatureService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccessibilityFeatureController extends Controller
{
    public function __construct(
        private AccessibilityFeatureService $service
    ) {}

    public function index(Request $request): View
    {
        $features = AccessibilityFeature::query()
            ->filterName($request->name)
            ->filterStatus($request->is_active)
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view(
                'pages.accessibility-features.partials.table',
                compact('features')
            );
        }

        return view(
            'pages.accessibility-features.index',
            compact('features')
        );
    }

    public function show(AccessibilityFeature $accessibilityFeature): View
    {
        return view(
            'pages.accessibility-features.show', [
            'feature' => $accessibilityFeature
        ]);
    }

    public function create(): View
    {
        return view('pages.accessibility-features.create');
    }

    public function store(AccessibilityFeatureRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()->route('accessibility-features.index')
            ->with('success', 'Recurso de acessibilidade criado com sucesso!');
    }

    public function edit(AccessibilityFeature $accessibilityFeature): View
    {
        return view(
            'pages.accessibility-features.edit',
            compact('accessibilityFeature')
        );
    }

    public function update(AccessibilityFeatureRequest $request, AccessibilityFeature $accessibilityFeature): RedirectResponse
    {
        $this->service->update($accessibilityFeature, $request->validated());

        return redirect()->route('accessibility-features.index')
            ->with('success', 'Recurso de acessibilidade atualizado com sucesso!');
    }

    public function destroy(AccessibilityFeature $accessibilityFeature): RedirectResponse
    {
        $this->service->delete($accessibilityFeature);

        return redirect()->route('accessibility-features.index')
            ->with('success', 'Recurso de acessibilidade removido com sucesso!');
    }
}

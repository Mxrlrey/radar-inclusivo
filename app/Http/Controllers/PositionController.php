<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionRequest;
use App\Models\Permission;
use App\Models\Position;
use App\Services\PositionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PositionController extends Controller
{
    public function __construct(
        private PositionService $service
    ) {}

    public function index(Request $request): View
    {
        $positions = Position::query()
            ->name($request->name)
            ->active($request->is_active)
            ->withCount('professionals')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        if ($request->ajax()) {
            return view(
                'pages.positions.partials.table',
                compact('positions')
            );
        }

        return view(
            'pages.positions.index',
            compact('positions')
        );
    }

    public function create(): View
    {
        return view(
            'pages.positions.create',
            $this->formData()
        );
    }

    public function store(PositionRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()
            ->route('cargos.index')
            ->with('success', 'Cargo criado com sucesso!');
    }

    public function show(Position $position): View
    {
        $position->load('permissions');
        return view('pages.positions.show', compact('position'));
    }

    public function edit(Position $position): View
    {
        return view(
            'pages.positions.edit',
            $this->formData() + [
                'position' => $position,
                'selectedPermissions' => $position->permissions->pluck('id')->toArray()
            ]
        );
    }

    public function update(PositionRequest $request, Position $position): RedirectResponse
    {
        $this->service->update($position, $request->validated());

        return redirect()
            ->route('cargos.index')
            ->with('success', 'Cargo atualizado com sucesso!');
    }

    public function toggleActive(Position $position): RedirectResponse
    {
        $this->service->update($position, ['is_active' => !$position->is_active]);

        $status = $position->wasChanged('is_active') && $position->is_active ? 'ativado' : 'desativado';

        return redirect()
            ->route('cargos.index')
            ->with('success', "Cargo {$status} com sucesso!");
    }

    public function destroy(Position $position): RedirectResponse
    {
        $this->service->delete($position);

        return redirect()
            ->route('cargos.index')
            ->with('success', 'Cargo removido com sucesso!');
    }

    /**
     * Prepara os dados para os formulários de Cargo
     */
    private function formData(): array
    {
        return [
            'permissions' => Permission::all()->groupBy(function ($permission) {
                $prefix = explode('.', $permission->slug)[0];
                // Tenta traduzir o prefixo da entidade, se não existir usa o prefixo puro
                $translated = __("permissions.entities.{$prefix}");
                return $translated !== "permissions.entities.{$prefix}" ? $translated : ucfirst($prefix);
            })
        ];
    }
}

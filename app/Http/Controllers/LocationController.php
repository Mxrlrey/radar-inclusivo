<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Models\Institution;
use App\Models\Location;
use App\Services\LocationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function __construct(
        private LocationService $service,
    ) {}

    public function index(Request $request)
    {
        $locations = Location::with('institution')
            ->filterName($request->name)
            ->filterInstitution($request->institution_name)
            ->filterActive($request->is_active)
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('pages.locations.partials.table', compact('locations'))->render();
        }

        return view('pages.locations.index', compact('locations'));
    }

    public function create(): View
    {
        return view('pages.locations.create',
            $this->formData() + ['selectedInstitution' => null]
        );
    }

    public function store(LocationRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()
            ->route('locations.index')
            ->with('success', 'Ponto de referência criado com sucesso!');
    }

    public function show(Location $location): View
    {
        return view('pages.locations.show', compact('location'));
    }

    public function edit(Location $location): View
    {
        $selectedInstitution = Institution::where('is_active', true)
            ->with('locations')
            ->find($location->institution_id);

        return view('pages.locations.edit',
            $this->formData() + [
                'location' => $location,
                'selectedInstitution' => $selectedInstitution,
            ]
        );
    }

    public function update(LocationRequest $request, Location $location): RedirectResponse
    {
        $this->service->update($location, $request->validated());

        return redirect()
            ->route('locations.index')
            ->with('success', 'Localização atualizada com sucesso!');
    }

    public function destroy(Location $location): RedirectResponse
    {
        $this->service->delete($location);

        return redirect()
            ->route('locations.index')
            ->with('success', 'Localização removida com sucesso!');
    }

    private function formData(): array
    {
        $institutions = Institution::where('is_active', true)
            ->with('locations')
            ->orderBy('name')
            ->get();

        return [
            'institutions' => $institutions->pluck('name', 'id'),
            'institutionsData' => $institutions->map(fn($inst) => [
                'id'           => $inst->id,
                'name'         => $inst->name,
                'latitude'     => $inst->latitude,
                'longitude'    => $inst->longitude,
                'default_zoom' => $inst->default_zoom ?? 16,
                'locations'    => $inst->locations,
            ])->values(),
        ];
    }
}

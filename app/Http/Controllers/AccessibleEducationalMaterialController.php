<?php

namespace App\Http\Controllers;

use App\Enums\ConservationState;
use App\Enums\InspectionType;
use App\Enums\ResourceStatus;
use App\Http\Requests\AccessibleEducationalMaterialRequest;
use App\Models\AccessibilityFeature;
use App\Models\AccessibleEducationalMaterial;
use App\Models\Deficiency;
use App\Models\Inspection;
use App\Services\AccessibleEducationalMaterialService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AccessibleEducationalMaterialController extends Controller
{
    public function __construct(
        private AccessibleEducationalMaterialService $service
    ) {}

    public function index(Request $request): View
    {
        $name   = trim($request->name ?? '');
        $status = ResourceStatus::tryFrom($request->status ?? '');

        $materials = AccessibleEducationalMaterial::with(['deficiencies', 'accessibilityFeatures'])
            ->filterName($name ?: null)
            ->active($request->is_active)
            ->digital($request->is_digital)
            ->available($request->available)
            ->when($status, fn($q) => $q->where('status', $status->value))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view(
                'pages.accessible-educational-materials.partials.table',
                compact('materials')
            );
        }

        return view(
            'pages.accessible-educational-materials.index',
            compact('materials')
        );
    }

    public function create(): View
    {
        return view(
            'pages.accessible-educational-materials.create',
            $this->formData(InspectionType::INITIAL->value)
        );
    }

    public function store(AccessibleEducationalMaterialRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()
            ->route('materiais-pedagogicos-acessiveis.index')
            ->with('success', 'Material criado com sucesso!');
    }

    public function show(AccessibleEducationalMaterial $material)
    {
        $material->load([
            'deficiencies' => fn($q) => $q->orderBy('name'),
            'accessibilityFeatures' => fn($q) => $q->orderBy('name'),
            'loans',
        ]);

        $inspections = $material->inspections()
            ->with(['images'])
            ->latest('inspection_date')
            ->latest('created_at')
            ->simplePaginate(3);

        if (request()->ajax()) {
            return view('pages.accessible-educational-materials.partials.inspections-table', [
                'material' => $material,
                'inspections' => $inspections
            ])->render();
        }

        return view('pages.accessible-educational-materials.show', [
            'material' => $material,
            'deficiencies' => $material->deficiencies,
            'features' => $material->accessibilityFeatures,
            'inspections'  => $inspections,
        ]);
    }

    public function edit(AccessibleEducationalMaterial $material): View
    {
        $material->load(['deficiencies', 'accessibilityFeatures', 'inspections.images']);

        return view(
            'pages.accessible-educational-materials.edit',
            $this->formData(InspectionType::PERIODIC->value) + [
                'material'    => $material,
                'activeLoans' => $material->loans()->whereNull('return_date')->count(),
            ]
        );
    }

    public function update(AccessibleEducationalMaterialRequest $request, AccessibleEducationalMaterial $material): RedirectResponse
    {
        $this->service->update($material, $request->validated());

        return redirect()
            ->route('materiais-pedagogicos-acessiveis.index')
            ->with('success', 'Material atualizado com sucesso!');
    }

    public function destroy(AccessibleEducationalMaterial $material): RedirectResponse
    {
        $this->service->delete($material);

        return redirect()
            ->route('materiais-pedagogicos-acessiveis.index')
            ->with('success', 'Material removido!');
    }

    public function generatePdf(AccessibleEducationalMaterial $material): Response
    {
        $material->load(['deficiencies', 'accessibilityFeatures', 'inspections.images']);

        $pdf = Pdf::loadView(
            'pages.accessible-educational-materials.pdf',
            compact('material')
        )
            ->setPaper('a4', 'portrait')
            ->setOption(['enable_php' => true]);

        return $pdf->stream("MPA_{$material->name}.pdf");
    }

    public function showInspection(AccessibleEducationalMaterial $material, Inspection $inspection): View
    {
        abort_if(
            $inspection->inspectable_id !== $material->id ||
            $inspection->inspectable_type !== $material->getMorphClass(),
            403
        );

        $inspection->load(['images', 'inspectable']);

        return view('pages.accessible-educational-materials.inspections.show', [
            'material'   => $material,
            'inspection' => $inspection,
        ]);
    }

    private function formData(string $defaultInspection): array
    {
        return [
            'deficiencies'          => Deficiency::orderBy('name')->get(),
            'accessibilityFeatures' => AccessibilityFeature::where('is_active', true)->orderBy('name')->get(),
            'resourceStatuses'      => collect(ResourceStatus::cases())
                ->mapWithKeys(fn($i) => [$i->value => $i->label()]),
            'conservationStates'    => collect(ConservationState::cases())
                ->mapWithKeys(fn($i) => [$i->value => $i->label()]),
            'inspectionTypes'       => collect(InspectionType::cases())
                ->mapWithKeys(fn($i) => [$i->value => $i->label()]),
            'defaultInspection'     => $defaultInspection,
            'defaultStatus'         => ResourceStatus::AVAILABLE->value,
        ];
    }
}

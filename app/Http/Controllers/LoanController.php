<?php

namespace App\Http\Controllers;

use App\Enums\LoanStatus;
use App\Enums\ResourceStatus;
use App\Models\{AccessibleEducationalMaterial, AssistiveTechnology, Loan, Student};
use App\Http\Requests\LoanRequest;
use App\Models\Professional;
use App\Services\LoanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function __construct(
        private LoanService $service
    ) {}

    public function index(Request $request): View
    {
        $loans = Loan::with(['loanable', 'student.person', 'professional.person', 'user'])
            ->student($request->student)
            ->professional($request->professional)
            ->item($request->item)
            ->byStatus($request->status ? LoanStatus::tryFrom($request->status) : null)
            ->orderByDesc('loan_date')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('pages.loans.partials.table', compact('loans'));
        }

        return view('pages.loans.index', compact('loans'));
    }

    public function create(Request $request): View
    {
        return view('pages.loans.create',
            $this->formData() + [
                'assistive_technologies' => $this->loanableItems(AssistiveTechnology::class),
                'educational_materials' => $this->loanableItems(AccessibleEducationalMaterial::class),
                'authUser' => auth()->user(),
                'selectedStudentId' => $request->query('student_id'),
                'selectedProfessionalId' => $request->query('professional_id'),
                'selectedItemId' => $request->query('item_id'),
                'selectedItemType' => $request->query('item_type'),
            ]
        );
    }

    public function store(LoanRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()
            ->route('emprestimos.index')
            ->with('success', 'Empréstimo realizado com sucesso!');
    }

    public function show(Loan $loan): View
    {
        $loan->load(['loanable', 'student.person', 'professional.person', 'user']);

        $currentStatus = $loan->status instanceof LoanStatus
            ? $loan->status
            : LoanStatus::tryFrom($loan->status);

        $isOverdue = $currentStatus === LoanStatus::ACTIVE && $loan->due_date->isPast();

        return view('pages.loans.show', [
            'loan' => $loan,
            'statusLabel' => $isOverdue ? 'Em Atraso' : ($currentStatus?->label() ?? $loan->status),
            'statusColor' => $isOverdue ? 'danger' : ($currentStatus?->color() ?? 'secondary'),
            'isOverdue' => $isOverdue,
            'authUser' => auth()->user(),
        ]);
    }

    public function edit(Loan $loan): View
    {
        $loan->load(['loanable', 'student.person', 'professional.person']);

        return view('pages.loans.edit',
            $this->formData() + [
                'loan' => $loan,
                'authUser' => auth()->user(),
            ]
        );
    }

    public function update(LoanRequest $request, Loan $loan): RedirectResponse
    {
        $this->service->update($loan, $request->validated());

        return redirect()
            ->route('emprestimos.index')
            ->with('success', 'Empréstimo atualizado com sucesso!');
    }

    public function returnItem(Request $request, Loan $loan): RedirectResponse
    {
        $this->service->markAsReturned($loan, [
            'is_damaged' => $request->boolean('is_damaged'),
            'observation' => $request->input('observation'),
        ]);

        return redirect()
            ->route('emprestimos.index')
            ->with('success', 'Devolução registrada com sucesso!');
    }

    public function destroy(Loan $loan): RedirectResponse
    {
        $this->service->delete($loan);

        return redirect()
            ->route('emprestimos.index')
            ->with('success', 'Registro de empréstimo removido com sucesso!');
    }

    public function generatePdf(Loan $loan): Response
    {
        $loan->load(['loanable', 'student.person', 'professional.person']);

        $pdf = Pdf::loadView('pages.loans.pdf', compact('loan'))
            ->setPaper('a4', 'portrait')
            ->setOption(['enable_php' => true]);

        return $pdf->stream("Loan_{$loan->id}.pdf");
    }

    private function formData(): array
    {
        return [
            'students' => Student::with('person')
                ->get()
                ->sortBy('person.name')
                ->mapWithKeys(fn($s) => [$s->id => $s->person?->name . " ({$s->registration})"]),

            'professionals' => Professional::with('person')
                ->get()
                ->sortBy('person.name')
                ->mapWithKeys(fn($p) => [$p->id => $p->person?->name . " - " . $p->registration]),
        ];
    }

    /**
     * Retorna itens disponíveis para empréstimo de um modelo específico,
     * já formatados para o select da view.
     */
    private function loanableItems(string $model): Collection
    {
        return $model::where('is_active', true)
            ->where('is_loanable', true)
            ->where('status', ResourceStatus::AVAILABLE)
            ->get()
            ->filter(fn($item) => $item->is_digital || ($item->quantity_available ?? 0) > 0)
            ->map(fn($item) => [
                'id' => $item->id,
                'name' => $item->name,
                'asset_code' => $item->asset_code ?? 'S/N',
                'is_digital' => (bool) $item->is_digital,
                'quantity_available' => $item->quantity_available,
            ])
            ->values();
    }
}

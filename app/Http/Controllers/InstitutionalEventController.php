<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesBackRoute;
use App\Http\Requests\InstitutionalEventRequest;
use App\Models\InstitutionalEvent;
use App\Services\InstitutionalEventService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstitutionalEventController extends Controller
{
    use ResolvesBackRoute;

    public function __construct(
        private InstitutionalEventService $service
    ) {}

    public function index(Request $request): View
    {
        $title = trim($request->title ?? '');

        $events = InstitutionalEvent::query()
            ->searchTitle($title ?: null)
            ->when($request->filled('is_active'), fn($query) => $query->active($request->is_active))
            ->orderBy('start_date')
            ->orderBy('start_time')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('pages.institutional-events.partials.table', compact('events'));
        }

        return view('pages.institutional-events.index', compact('events'));
    }

    public function create(Request $request): View
    {
        $backRoute = $this->resolveBackRoute($request, 'institutional-events.index');

        return view('pages.institutional-events.create', compact('backRoute'));
    }

    public function store(InstitutionalEventRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()
            ->route('institutional-events.index')
            ->with('success', 'Evento criado com sucesso!');
    }

    public function show(Request $request, InstitutionalEvent $event): View
    {
        $backRoute = $this->resolveBackRoute($request, 'institutional-events.index');

        return view('pages.institutional-events.show', compact('event', 'backRoute'));
    }

    public function edit(Request $request, InstitutionalEvent $event): View
    {
        return view('pages.institutional-events.edit', compact('event'));
    }

    public function update(InstitutionalEventRequest $request, InstitutionalEvent $event): RedirectResponse
    {
        $this->service->update($event, $request->validated());

        return redirect()
            ->route('institutional-events.index')
            ->with('success', 'Evento atualizado com sucesso!');
    }

    public function destroy(InstitutionalEvent $event): RedirectResponse
    {
        $this->service->delete($event);

        return redirect()
            ->route('institutional-events.index')
            ->with('success', 'Evento removido com sucesso!');
    }

    public function generatePdf(InstitutionalEvent $event)
    {
        $pdf = Pdf::loadView(
            'pages.institutional-events.pdf',
            compact('event')
        )
            ->setPaper('a4', 'portrait')
            ->setOption(['enable_php' => true]);

        return $pdf->stream("Evento_{$event->id}.pdf");
    }

}

<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $service
    ) {}

    public function builder(): View
    {
        return view('pages.reports.builder');
    }

    public function availableEntities(): JsonResponse
    {
        return response()->json(
            $this->service->availableEntities()
        );
    }

    public function meta(Request $request): JsonResponse
    {
        try {
            return response()->json(
                $this->service->meta($request->input('model'))
            );
        } catch (Throwable $e) {
            return response()->json(
                ['error' => $e->getMessage()],
                400
            );
        }
    }

    public function run(Request $request): JsonResponse
    {
        try {
            return response()->json(
                $this->service->run($request->all())
            );
        } catch (Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $payload = $request->filled('payload')
                ? json_decode($request->input('payload'), true, 512, JSON_THROW_ON_ERROR)
                : $request->all();

            $data = $this->service->exportData($payload, 1000);

            $pdf = Pdf::loadView('pages.reports.pdf', [
                'data' => $data['rows'] ?? [],
                'headers' => $payload['headers'] ?? [],
            ]);

            return $pdf->download('relatorio.pdf');
        } catch (Throwable $e) {
            return response()->json(
                ['error' => $e->getMessage()],
                500
            );
        }
    }
}

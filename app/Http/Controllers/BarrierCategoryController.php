<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarrierCategoryRequest;
use App\Models\BarrierCategory;
use App\Services\BarrierCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarrierCategoryController extends Controller
{
    public function __construct(
        private BarrierCategoryService $service
    ) {}

    public function index(Request $request): View|string
    {
        $categories = BarrierCategory::withCount('barriers')
            ->filterName($request->name)
            ->filterActive($request->is_active)
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('pages.barrier-categories.partials.table', compact('categories'))->render();
        }

        return view(
            'pages.barrier-categories.index',
            compact('categories')
        );
    }

    public function create(): View
    {
        return view('pages.barrier-categories.create');
    }

    public function store(BarrierCategoryRequest $request): RedirectResponse
    {
        $this->service->store($request->validated());

        return redirect()
            ->route('categorias-de-barreiras.index')
            ->with('success', 'Categoria de barreira cadastrada com sucesso!');
    }

    public function show(BarrierCategory $barrierCategory): View
    {
        return view(
            'pages.barrier-categories.show',
            compact('barrierCategory')
        );
    }

    public function edit(BarrierCategory $barrierCategory): View
    {
        return view(
            'pages.barrier-categories.edit',
            compact('barrierCategory')
        );
    }

    public function update(BarrierCategoryRequest $request, BarrierCategory $barrierCategory): RedirectResponse
    {
        $this->service->update($barrierCategory, $request->validated());

        return redirect()
            ->route('categorias-de-barreiras.index')
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(BarrierCategory $barrierCategory): RedirectResponse
    {
        $this->service->delete($barrierCategory);

        return redirect()
            ->route('categorias-de-barreiras.index')
            ->with('success', 'Categoria removida com sucesso!');
    }
}

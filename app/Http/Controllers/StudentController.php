<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Http\Requests\StudentRequest;
use App\Models\Deficiency;
use App\Models\Person;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function __construct(
        private StudentService $service
    ) {}

    public function index(Request $request): View
    {
        $students = Student::with('person')
            ->name($request->name)
            ->registration($request->registration)
            ->email($request->email)
            ->active($request->is_active)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        if ($request->ajax()) {
            return view('pages.students.partials.table', compact('students'));
        }

        return view('pages.students.index', compact('students'));
    }

    public function create(): View
    {
        return view(
            'pages.students.create',
            $this->formData()
        );
    }

    public function store(StudentRequest $request): RedirectResponse
    {
        $student = $this->service->store($request->validated());

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Aluno cadastrado com sucesso!');
    }

    public function show(Student $student): View
    {
        return view('pages.students.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        return view(
            'pages.students.edit',
            $this->formData() + ['student' => $student]
        );
    }

    public function update(StudentRequest $request, Student $student): RedirectResponse
    {
        $this->service->update($student, array_merge(
            $request->validated(),
            $request->only('photo', 'remove_photo')
        ));

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Dados do aluno atualizados com sucesso!');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $this->service->delete($student);

        return redirect()
            ->route('students.index')
            ->with('success', 'Aluno removido com sucesso!');
    }

    /**
     * Centraliza os dados necessários para os formulários de Aluno
     */
    private function formData(): array
    {
        return [
            'genders' => Gender::options(),
        ];
    }
}

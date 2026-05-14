<?php

namespace Tests\Feature;

use App\Enums\Gender;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Arrange
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->regularUser = User::factory()->create(['is_admin' => false]);
    }

    public function test_guest_cannot_access_students_index()
    {
        // Act
        $response = $this->get(route('estudantes.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_students_index()
    {
        // Act
        $response = $this->actingAs($this->regularUser)->get(route('estudantes.index'));

        // Assert
        $response->assertForbidden();
    }

    public function test_admin_can_list_students()
    {
        // Arrange
        Student::factory()->count(2)->create();

        // Act
        $response = $this->actingAs($this->admin)->get(route('estudantes.index'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.students.index');
        $response->assertViewHas('students');
    }

    public function test_students_index_returns_partial_when_ajax()
    {
        // Arrange
        Student::factory()->count(2)->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('estudantes.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.students.partials.table');
    }

    public function test_admin_can_access_student_create_page()
    {
        // Act
        $response = $this->actingAs($this->admin)->get(route('estudantes.criar'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.students.create');
        $response->assertViewHas('genders');
    }

    public function test_admin_can_store_student()
    {
        // Arrange
        $data = $this->validStudentData([
            'name' => 'Aluno Teste',
            'email' => 'aluno.teste@example.com',
            'document' => '52998224725',
            'registration' => 'MAT-001',
        ]);

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('estudantes.salvar'), $data);

        // Assert
        $student = Student::where('registration', 'MAT-001')->first();

        $response->assertRedirect(route('estudantes.visualizar', $student));
        $this->assertDatabaseHas('people', ['email' => 'aluno.teste@example.com']);
        $this->assertDatabaseHas('students', ['registration' => 'MAT-001']);
    }

    public function test_store_requires_student_name()
    {
        // Arrange
        $data = $this->validStudentData(['name' => '']);

        // Act
        $response = $this->actingAs($this->admin)
            ->post(route('estudantes.salvar'), $data);

        // Assert
        $response->assertSessionHasErrors('name');
    }

    public function test_admin_can_view_student()
    {
        // Arrange
        $student = Student::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('estudantes.visualizar', $student));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.students.show');
        $response->assertViewHas('student', $student);
    }

    public function test_admin_can_access_student_edit_page()
    {
        // Arrange
        $student = Student::factory()->create();

        // Act
        $response = $this->actingAs($this->admin)
            ->get(route('estudantes.editar', $student));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.students.edit');
        $response->assertViewHas('student', $student);
        $response->assertViewHas('genders');
    }

    public function test_admin_can_update_student()
    {
        // Arrange
        $student = Student::factory()->create();

        $data = $this->validStudentData([
            'name' => 'Aluno Atualizado',
            'email' => 'aluno.atualizado@example.com',
            'document' => $student->person->document,
            'registration' => $student->registration,
        ]);

        // Act
        $response = $this->actingAs($this->admin)
            ->put(route('estudantes.atualizar', $student), $data);

        // Assert
        $response->assertRedirect(route('estudantes.index'));
        $this->assertDatabaseHas('people', [
            'id' => $student->person_id,
            'name' => 'Aluno Atualizado',
        ]);
    }

    public function test_admin_can_delete_student()
    {
        // Arrange
        $student = Student::factory()->create();
        $personId = $student->person_id;

        // Act
        $response = $this->actingAs($this->admin)
            ->delete(route('estudantes.excluir', $student));

        // Assert
        $response->assertRedirect(route('estudantes.index'));
        $this->assertSoftDeleted('students', ['id' => $student->id]);
        $this->assertSoftDeleted('people', ['id' => $personId]);
    }

    private function validStudentData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Aluno Radar',
            'email' => 'aluno.radar@example.com',
            'document' => '52998224725',
            'birth_date' => '2002-01-10',
            'gender' => Gender::NOT_SPECIFIED->value,
            'phone' => '77999999999',
            'address' => 'Rua de Teste, 100',
            'registration' => 'MAT-BASE',
            'entry_date' => '2024-02-01',
            'is_active' => true,
        ], $overrides);
    }
}

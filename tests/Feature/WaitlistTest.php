<?php

namespace Tests\Feature;

use App\Enums\WaitlistStatus;
use App\Models\AssistiveTechnology;
use App\Models\Waitlist;
use App\Models\Professional;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WaitlistTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->regularUser = User::factory()->create(['is_admin' => false]);
    }

    public function test_guest_cannot_access_waitlists_index()
    {
        $response = $this->get(route('filas-de-espera.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_waitlists_index()
    {
        $response = $this->actingAs($this->regularUser)
            ->get(route('filas-de-espera.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_list_waitlists()
    {
        Waitlist::factory()->count(2)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('filas-de-espera.index'));

        $response->assertOk();
        $response->assertViewIs('pages.waitlists.index');
        $response->assertViewHas('waitlists');
    }

    public function test_waitlists_index_returns_partial_when_ajax()
    {
        Waitlist::factory()->count(2)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('filas-de-espera.index'), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertOk();
        $response->assertViewIs('pages.waitlists.partials.table');
        $response->assertViewHas('waitlists');
    }

    public function test_admin_can_access_waitlist_create_page()
    {
        Student::factory()->create();
        Professional::factory()->create();
        AssistiveTechnology::factory()->physical()->unavailable()->create([
            'quantity' => 1,
            'quantity_available' => 0,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('filas-de-espera.criar'));

        $response->assertOk();
        $response->assertViewIs('pages.waitlists.create');
        $response->assertViewHas('students');
        $response->assertViewHas('professionals');
        $response->assertViewHas('assistive_technologies');
        $response->assertViewHas('educational_materials');
    }

    public function test_admin_can_store_a_waitlist()
    {
        $student = Student::factory()->create();
        $item = AssistiveTechnology::factory()->physical()->unavailable()->create([
            'quantity' => 1,
            'quantity_available' => 0,
        ]);

        $data = [
            'waitlistable_id' => $item->id,
            'waitlistable_type' => 'assistive_technology',
            'student_id' => $student->id,
            'user_id' => $this->admin->id,
            'observation' => 'Entrou na fila',
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('filas-de-espera.salvar'), $data);

        $response->assertRedirect(route('filas-de-espera.index'));
        $this->assertDatabaseHas('waitlists', [
            'waitlistable_id' => $item->id,
            'waitlistable_type' => $item->getMorphClass(),
            'student_id' => $student->id,
        ]);
    }

    public function test_admin_can_view_a_waitlist()
    {
        $waitlist = Waitlist::factory()->create([
            'status' => WaitlistStatus::WAITING->value,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('filas-de-espera.visualizar', $waitlist));

        $response->assertOk();
        $response->assertViewIs('pages.waitlists.show');
        $response->assertViewHas('waitlist');
        $response->assertViewHas('canCancel', true);
    }

    public function test_admin_can_access_waitlist_edit_page()
    {
        $waitlist = Waitlist::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('filas-de-espera.editar', $waitlist));

        $response->assertOk();
        $response->assertViewIs('pages.waitlists.edit');
        $response->assertViewHas('waitlist');
        $response->assertViewHas('students');
        $response->assertViewHas('professionals');
    }

    public function test_admin_can_update_a_waitlist()
    {
        $waitlist = Waitlist::factory()->create([
            'status' => WaitlistStatus::WAITING->value,
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('filas-de-espera.atualizar', $waitlist), [
                'status' => WaitlistStatus::NOTIFIED->value,
                'observation' => 'Avisado',
            ]);

        $response->assertRedirect(route('filas-de-espera.index'));
        $this->assertDatabaseHas('waitlists', [
            'id' => $waitlist->id,
            'status' => WaitlistStatus::NOTIFIED->value,
            'observation' => 'Avisado',
        ]);
    }

    public function test_admin_can_cancel_a_waitlist()
    {
        $waitlist = Waitlist::factory()->create([
            'status' => WaitlistStatus::WAITING->value,
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('filas-de-espera.cancelar', $waitlist));

        $response->assertRedirect();
        $this->assertDatabaseHas('waitlists', [
            'id' => $waitlist->id,
            'status' => WaitlistStatus::CANCELLED->value,
        ]);
    }

    public function test_admin_can_delete_a_waitlist()
    {
        $waitlist = Waitlist::factory()->create([
            'status' => WaitlistStatus::WAITING->value,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('filas-de-espera.excluir', $waitlist));

        $response->assertRedirect(route('filas-de-espera.index'));
        $this->assertDatabaseMissing('waitlists', [
            'id' => $waitlist->id,
        ]);
    }

    public function test_admin_can_generate_waitlist_pdf()
    {
        $waitlist = Waitlist::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('filas-de-espera.pdf', $waitlist));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}

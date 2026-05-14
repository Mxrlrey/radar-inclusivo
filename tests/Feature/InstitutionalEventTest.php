<?php

namespace Tests\Feature;

use App\Models\InstitutionalEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstitutionalEventTest extends TestCase
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

    public function test_guest_cannot_access_institutional_events_index()
    {
        $response = $this->get(route('agenda-institucional.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_institutional_events_index()
    {
        $response = $this->actingAs($this->regularUser)
            ->get(route('agenda-institucional.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_list_institutional_events()
    {
        InstitutionalEvent::factory()->count(2)->create([
            'end_date' => now()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'location' => 'Auditorio Central',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('agenda-institucional.index'));

        $response->assertOk();
        $response->assertViewIs('pages.institutional-events.index');
        $response->assertViewHas('events');
    }

    public function test_institutional_events_index_returns_partial_when_ajax()
    {
        InstitutionalEvent::factory()->count(2)->create();

        $response = $this->actingAs($this->admin)
            ->get(route('agenda-institucional.index'), [
                'X-Requested-With' => 'XMLHttpRequest',
            ]);

        $response->assertOk();
        $response->assertViewIs('pages.institutional-events.partials.table');
        $response->assertViewHas('events');
    }

    public function test_admin_can_access_institutional_event_create_page()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('agenda-institucional.criar', ['back' => route('agenda-institucional.index')]));

        $response->assertOk();
        $response->assertViewIs('pages.institutional-events.create');
        $response->assertViewHas('backRoute', route('agenda-institucional.index'));
    }

    public function test_admin_can_store_an_institutional_event()
    {
        $data = [
            'title' => 'Feira de Inclusao',
            'description' => 'Evento institucional do radar',
            'start_date' => now()->toDateString(),
            'end_date' => now()->toDateString(),
            'start_time' => '08:00',
            'end_time' => '10:00',
            'location' => 'Auditorio',
            'organizer' => 'Equipe Radar',
            'audience' => 'Comunidade',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('agenda-institucional.salvar'), $data);

        $response->assertRedirect(route('agenda-institucional.index'));
        $this->assertDatabaseHas('institutional_events', [
            'title' => 'Feira de Inclusao',
            'location' => 'Auditorio',
        ]);
    }

    public function test_admin_can_view_an_institutional_event()
    {
        $event = InstitutionalEvent::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('agenda-institucional.visualizar', [
                'event' => $event,
                'back' => route('agenda-institucional.index'),
            ]));

        $response->assertOk();
        $response->assertViewIs('pages.institutional-events.show');
        $response->assertViewHas('event', $event);
        $response->assertViewHas('backRoute', route('agenda-institucional.index'));
    }

    public function test_admin_can_access_institutional_event_edit_page()
    {
        $event = InstitutionalEvent::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('agenda-institucional.editar', $event));

        $response->assertOk();
        $response->assertViewIs('pages.institutional-events.edit');
        $response->assertViewHas('event', $event);
    }

    public function test_admin_can_update_an_institutional_event()
    {
        $event = InstitutionalEvent::factory()->create([
            'start_date' => now()->toDateString(),
            'end_date' => now()->toDateString(),
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'location' => 'Sala Multiuso',
        ]);

        $data = [
            'title' => 'Evento Atualizado',
            'description' => 'Descricao atualizada',
            'start_date' => $event->start_date->toDateString(),
            'end_date' => $event->end_date->toDateString(),
            'start_time' => '09:00',
            'end_time' => '11:00',
            'location' => 'Biblioteca',
            'organizer' => 'Equipe Atualizada',
            'audience' => 'Publico geral',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('agenda-institucional.atualizar', $event), $data);

        $response->assertRedirect(route('agenda-institucional.index'));
        $this->assertDatabaseHas('institutional_events', [
            'id' => $event->id,
            'title' => 'Evento Atualizado',
            'location' => 'Biblioteca',
        ]);
    }

    public function test_admin_can_delete_an_institutional_event()
    {
        $event = InstitutionalEvent::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('agenda-institucional.excluir', $event));

        $response->assertRedirect(route('agenda-institucional.index'));
        $this->assertDatabaseMissing('institutional_events', [
            'id' => $event->id,
        ]);
    }

    public function test_admin_can_generate_institutional_event_pdf()
    {
        $event = InstitutionalEvent::factory()->create();

        $response = $this->actingAs($this->admin)
            ->get(route('agenda-institucional.pdf', $event));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}

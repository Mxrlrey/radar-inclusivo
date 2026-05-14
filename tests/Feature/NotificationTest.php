<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Arrange
        $this->user = User::factory()->create(['is_admin' => true]);
    }

    public function test_guest_cannot_access_notifications_index()
    {
        // Act
        $response = $this->get(route('notificacoes.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_list_notifications()
    {
        // Arrange
        $this->createNotification($this->user);

        // Act
        $response = $this->actingAs($this->user)->get(route('notificacoes.index'));

        // Assert
        $response->assertOk();
        $response->assertViewIs('pages.notifications.index');
        $response->assertViewHas('notifications');
    }

    public function test_authenticated_user_can_get_unread_notifications_count()
    {
        // Arrange
        $this->createNotification($this->user);
        $this->createNotification($this->user, ['read_at' => now()]);

        // Act
        $response = $this->actingAs($this->user)->getJson(route('notificacoes.quantidade'));

        // Assert
        $response->assertOk();
        $response->assertJson(['count' => 1]);
    }

    public function test_authenticated_user_can_get_notifications_list()
    {
        // Arrange
        $notificationId = $this->createNotification($this->user);

        // Act
        $response = $this->actingAs($this->user)->getJson(route('notificacoes.lista'));

        // Assert
        $response->assertOk();
        $response->assertJsonFragment(['id' => $notificationId]);
    }

    public function test_authenticated_user_can_mark_notification_as_read()
    {
        // Arrange
        $notificationId = $this->createNotification($this->user);

        // Act
        $response = $this->actingAs($this->user)
            ->from(route('notificacoes.index'))
            ->post(route('notificacoes.ler', $notificationId));

        // Assert
        $response->assertRedirect(route('notificacoes.index'));
        $this->assertNotNull(
            DB::table('notifications')->where('id', $notificationId)->value('read_at')
        );
    }

    public function test_mark_notification_as_read_returns_error_when_not_found()
    {
        // Act
        $response = $this->actingAs($this->user)
            ->from(route('notificacoes.index'))
            ->post(route('notificacoes.ler', (string) Str::uuid()));

        // Assert
        $response->assertRedirect(route('notificacoes.index'));
        $response->assertSessionHas('error', 'Notificação não encontrada.');
    }

    public function test_authenticated_user_can_mark_all_notifications_as_read()
    {
        // Arrange
        $firstId = $this->createNotification($this->user);
        $secondId = $this->createNotification($this->user);

        // Act
        $response = $this->actingAs($this->user)
            ->post(route('notificacoes.ler.todas'));

        // Assert
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Todas notificações foram lidas.');
        $this->assertNotNull(DB::table('notifications')->where('id', $firstId)->value('read_at'));
        $this->assertNotNull(DB::table('notifications')->where('id', $secondId)->value('read_at'));
    }

    private function createNotification(User $user, array $overrides = []): string
    {
        $id = (string) Str::uuid();

        DB::table('notifications')->insert(array_merge([
            'id' => $id,
            'type' => 'Tests\\Feature\\FakeNotification',
            'notifiable_type' => $user->getMorphClass(),
            'notifiable_id' => $user->id,
            'data' => json_encode(['message' => 'Mensagem de teste']),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));

        return $id;
    }
}

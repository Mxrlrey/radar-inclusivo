<?php

namespace Tests\Unit;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Tests\TestCase;

class ControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_exception_redirects_back_with_exception_message()
    {
        // Arrange
        Log::shouldReceive('error')->once();

        $controller = new class extends Controller {
            public function handle(RuntimeException $exception)
            {
                return $this->handleException($exception, 'Mensagem padrão.');
            }
        };

        // Act
        $this->from('/origem')->followingRedirects(false);
        $redirect = $controller->handle(new RuntimeException('Falha específica.'));

        // Assert
        $this->assertSame('Falha específica.', $redirect->getSession()->get('error'));
        $this->assertSame('/origem', $redirect->getTargetUrl());
    }

    public function test_handle_exception_uses_fallback_message_when_exception_message_is_empty()
    {
        // Arrange
        Log::shouldReceive('error')->once();

        $controller = new class extends Controller {
            public function handle(RuntimeException $exception)
            {
                return $this->handleException($exception, 'Mensagem padrão.');
            }
        };

        // Act
        $this->from('/origem');
        $redirect = $controller->handle(new RuntimeException());

        // Assert
        $this->assertSame('Mensagem padrão.', $redirect->getSession()->get('error'));
        $this->assertSame('/origem', $redirect->getTargetUrl());
    }
}

<?php

namespace Tests\Unit;

use App\Http\Requests\BackupRequest;
use App\Http\Requests\Mail\SessionNotification;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use PHPUnit\Framework\TestCase;

class HttpTest extends TestCase
{
    public function test_backup_request_authorizes_and_has_no_rules(): void
    {
        $request = new BackupRequest();

        $this->assertTrue($request->authorize());
        $this->assertSame([], $request->rules());
    }

    public function test_session_notification_mailable_sets_envelope_and_content(): void
    {
        $mailable = new SessionNotification(
            session: (object) ['id' => 10],
            title: 'Lembrete de atendimento',
            messageContent: 'Atendimento inicia em breve.'
        );

        $envelope = $mailable->envelope();
        $content = $mailable->content();

        $this->assertInstanceOf(Envelope::class, $envelope);
        $this->assertSame('Lembrete de atendimento', $envelope->subject);
        $this->assertInstanceOf(Content::class, $content);
        $this->assertSame('emails.specialized-educational-support.session-notification', $content->view);
    }
}

<?php

declare(strict_types=1);

namespace Serixa\Whatsapp\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Firebase\NotificationMessage;
use Serixa\Whatsapp\FakeWhatsAppClient;
use Serixa\Whatsapp\WhatsAppNotifier;

final class WhatsAppNotifierTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $result = WhatsAppNotifier::make(new FakeWhatsAppClient())
            ->send(NotificationMessage::make('15551234567', 'Hello', 'Serixa'));
        self::assertTrue($result->success);
        self::assertSame('whatsapp', $result->provider);
    }
}

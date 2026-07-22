<?php

declare(strict_types=1);

namespace Serixa\Telegram\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Firebase\NotificationMessage;
use Serixa\Telegram\FakeTelegramClient;
use Serixa\Telegram\TelegramNotifier;

final class TelegramNotifierTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $result = TelegramNotifier::make(new FakeTelegramClient())
            ->send(NotificationMessage::make('12345', 'Hello', 'Serixa'));
        self::assertTrue($result->success);
        self::assertSame('telegram', $result->provider);
    }
}

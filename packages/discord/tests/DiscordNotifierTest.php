<?php

declare(strict_types=1);

namespace Serixa\Discord\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Discord\DiscordNotifier;
use Serixa\Discord\FakeDiscordClient;
use Serixa\Firebase\NotificationMessage;

final class DiscordNotifierTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $result = DiscordNotifier::make(new FakeDiscordClient())
            ->send(NotificationMessage::make('https://discord.example/webhook', 'Hello', 'Serixa'));
        self::assertTrue($result->success);
        self::assertSame('discord', $result->provider);
    }
}

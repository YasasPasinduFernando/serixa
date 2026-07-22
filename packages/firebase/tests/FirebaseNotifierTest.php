<?php

declare(strict_types=1);

namespace Serixa\Firebase\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Firebase\FakeFirebaseClient;
use Serixa\Firebase\FirebaseNotifier;
use Serixa\Firebase\MemoryNotifier;
use Serixa\Firebase\NotificationMessage;

final class FirebaseNotifierTest extends TestCase
{
    public function testMemoryNotifier(): void
    {
        $notifier = new MemoryNotifier();
        $result = $notifier->send(NotificationMessage::make('token-1', 'Hello', 'Title'));
        self::assertTrue($result->success);
        self::assertCount(1, $notifier->sent());
    }

    public function testFakeClientRoundTrip(): void
    {
        $client = new FakeFirebaseClient();
        $notifier = FirebaseNotifier::make($client);
        $result = $notifier->send(NotificationMessage::make('device-token', 'Ping', 'Serixa'));
        self::assertTrue($result->success);
        self::assertSame('firebase', $result->provider);
        self::assertSame('device-token', $client->sent()[0]['token']);
    }
}

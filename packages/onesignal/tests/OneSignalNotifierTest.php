<?php

declare(strict_types=1);

namespace Serixa\Onesignal\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Firebase\NotificationMessage;
use Serixa\Onesignal\FakeOneSignalClient;
use Serixa\Onesignal\OneSignalNotifier;

final class OneSignalNotifierTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $result = OneSignalNotifier::make(new FakeOneSignalClient())
            ->send(NotificationMessage::make('player-1', 'Hello', 'Title'));
        self::assertTrue($result->success);
        self::assertSame('onesignal', $result->provider);
    }
}

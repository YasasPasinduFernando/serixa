<?php

declare(strict_types=1);

namespace Serixa\Slack\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Firebase\NotificationMessage;
use Serixa\Slack\FakeSlackClient;
use Serixa\Slack\SlackNotifier;

final class SlackNotifierTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $result = SlackNotifier::make(new FakeSlackClient())
            ->send(NotificationMessage::make('#general', 'Deployed', 'Serixa'));
        self::assertTrue($result->success);
        self::assertSame('slack', $result->provider);
    }
}

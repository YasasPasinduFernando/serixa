<?php

declare(strict_types=1);

namespace Serixa\Twilio\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Firebase\NotificationMessage;
use Serixa\Twilio\FakeTwilioClient;
use Serixa\Twilio\TwilioNotifier;

final class TwilioNotifierTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $result = TwilioNotifier::make(new FakeTwilioClient(), '+10000000000')
            ->send(NotificationMessage::make('+15551234567', 'OTP 1234', 'Code'));
        self::assertTrue($result->success);
        self::assertSame('twilio', $result->provider);
    }
}

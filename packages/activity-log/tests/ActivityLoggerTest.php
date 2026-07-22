<?php

declare(strict_types=1);

namespace Serixa\ActivityLog\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\ActivityLog\ActivityLogger;

final class ActivityLoggerTest extends TestCase
{
    public function testLogAndLatest(): void
    {
        $logger = ActivityLogger::memory();
        $logger->log('Created lead', 'u1', 'lead', '10');
        $logger->log('Updated lead', 'u1', 'lead', '10');

        self::assertSame(2, $logger->store()->count());
        self::assertCount(2, $logger->store()->forSubject('lead', '10'));
        self::assertSame('Updated lead', $logger->store()->latest(1)[0]->description());
    }
}

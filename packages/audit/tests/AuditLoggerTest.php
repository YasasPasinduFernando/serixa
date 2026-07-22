<?php

declare(strict_types=1);

namespace Serixa\Audit\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\ActivityLog\ActivityLogger;
use Serixa\Audit\AuditLogger;

final class AuditLoggerTest extends TestCase
{
    public function testRecordImmutableTrail(): void
    {
        $audit = AuditLogger::memory();
        $audit->record('role.assigned', 'admin', 'user', 'u1', ['role' => 'editor']);

        self::assertSame(1, $audit->store()->count());
        self::assertSame('role.assigned', $audit->store()->latest(1)[0]->action());
    }

    public function testMirrorsToActivityLog(): void
    {
        $activity = ActivityLogger::memory();
        $audit = AuditLogger::memory($activity);
        $audit->record('permission.revoked', 'admin', 'user', 'u2');

        self::assertSame(1, $activity->store()->count());
        self::assertStringStartsWith('audit:', $activity->store()->latest(1)[0]->description());
    }
}

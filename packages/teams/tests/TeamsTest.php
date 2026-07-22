<?php

declare(strict_types=1);

namespace Serixa\Teams\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Organization\Organizations;
use Serixa\Teams\Exceptions\TeamException;
use Serixa\Teams\Teams;

final class TeamsTest extends TestCase
{
    public function testCreateTeamInOrg(): void
    {
        $orgs = Organizations::memory();
        $orgs->create('acme', 'Acme');
        $teams = Teams::memory($orgs->store());
        $teams->create('sales', 'acme', 'Sales');
        $teams->addMember('sales', 'u1');

        self::assertTrue($teams->isMember('sales', 'u1'));
        self::assertCount(1, $teams->forOrganization('acme'));
    }

    public function testMissingOrgThrows(): void
    {
        $orgs = Organizations::memory();
        $teams = Teams::memory($orgs->store());
        $this->expectException(TeamException::class);
        $teams->create('x', 'missing', 'X');
    }
}

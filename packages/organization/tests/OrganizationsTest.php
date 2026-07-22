<?php

declare(strict_types=1);

namespace Serixa\Organization\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Organization\Exceptions\OrganizationException;
use Serixa\Organization\Organizations;
use Serixa\Organization\OrgContext;

final class OrganizationsTest extends TestCase
{
    public function testCreateAndMembership(): void
    {
        $orgs = Organizations::memory();
        $orgs->create('acme', 'Acme Inc', 'acme');
        $orgs->addMember('acme', 'u1', 'owner');

        self::assertTrue($orgs->isMember('acme', 'u1'));
        self::assertFalse($orgs->isMember('acme', 'u2'));
        self::assertCount(1, $orgs->store()->membersOf('acme'));
    }

    public function testOrgContext(): void
    {
        $ctx = OrgContext::memory();
        $ctx->store()->put(\Serixa\Organization\Organization::make('acme', 'Acme'));
        $ctx->setCurrent('acme');
        self::assertSame('acme', $ctx->requireCurrent()->id());
    }

    public function testMissingContextThrows(): void
    {
        $this->expectException(OrganizationException::class);
        OrgContext::memory()->requireCurrent();
    }
}

<?php

declare(strict_types=1);

namespace Serixa\Passkeys\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Passkeys\Passkeys;

final class PasskeysTest extends TestCase
{
    public function testRegisterFlow(): void
    {
        $pk = Passkeys::memory();
        $challenge = $pk->beginRegistration('user-1');
        $cred = $pk->register('cred-1', 'user-1', 'pk-bytes', $challenge->id());
        self::assertSame('cred-1', $cred->id());
        self::assertCount(1, $pk->credentialsFor('user-1'));
    }
}

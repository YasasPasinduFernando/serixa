<?php

declare(strict_types=1);

namespace Serixa\GithubOauth\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\GithubOauth\FakeGitHubOAuthClient;
use Serixa\GithubOauth\GitHubOAuthProvider;

final class GitHubOAuthProviderTest extends TestCase
{
    public function testAuthFlow(): void
    {
        $provider = GitHubOAuthProvider::make(new FakeGitHubOAuthClient(), 'cid', 'secret');
        $token = $provider->exchangeCode('x', 'https://app.test/cb');
        self::assertSame('dev@github.com', $provider->fetchUser($token)->email);
    }
}

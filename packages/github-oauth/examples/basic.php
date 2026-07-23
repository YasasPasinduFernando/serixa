<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\GithubOauth\FakeGitHubOAuthClient;
use Serixa\GithubOauth\GitHubOAuthProvider;

$u = GitHubOAuthProvider::make(new FakeGitHubOAuthClient(), 'cid', 'secret')
    ->fetchUser(GitHubOAuthProvider::make(new FakeGitHubOAuthClient(), 'cid', 'secret')->exchangeCode('s', 'https://app.test/cb'));
echo $u->provider === 'github' ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($u->provider === 'github' ? 0 : 1);

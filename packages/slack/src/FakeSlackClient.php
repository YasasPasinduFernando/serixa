<?php

declare(strict_types=1);

namespace Serixa\Slack;

use Serixa\Slack\Contracts\SlackClient;

final class FakeSlackClient implements SlackClient
{
    private int $seq = 0;

    public function postMessage(string $channel, string $text): array
    {
        ++$this->seq;

        return ['success' => true, 'id' => 'slack-' . $this->seq];
    }
}

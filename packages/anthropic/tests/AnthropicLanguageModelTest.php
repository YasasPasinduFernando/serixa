<?php

declare(strict_types=1);

namespace Serixa\Anthropic\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Anthropic\AnthropicLanguageModel;
use Serixa\Anthropic\FakeAnthropicClient;

final class AnthropicLanguageModelTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $model = AnthropicLanguageModel::make(new FakeAnthropicClient());
        $response = $model->complete('hello');
        self::assertSame('anthropic: hello', $response->content);
    }
}

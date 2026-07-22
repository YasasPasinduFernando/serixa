<?php

declare(strict_types=1);

namespace Serixa\Deepseek\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Deepseek\DeepSeekLanguageModel;
use Serixa\Deepseek\FakeDeepSeekClient;

final class DeepSeekLanguageModelTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $model = DeepSeekLanguageModel::make(new FakeDeepSeekClient());
        $response = $model->complete('hello');
        self::assertSame('deepseek: hello', $response->content);
        self::assertSame('deepseek-chat', $response->model);
    }
}

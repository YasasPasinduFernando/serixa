<?php

declare(strict_types=1);

namespace Serixa\Gemini\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Gemini\FakeGeminiClient;
use Serixa\Gemini\GeminiLanguageModel;

final class GeminiLanguageModelTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $model = GeminiLanguageModel::make(new FakeGeminiClient());
        $response = $model->complete('hello');
        self::assertSame('gemini: hello', $response->content);
        self::assertSame('gemini-1.5-flash', $response->model);
    }
}

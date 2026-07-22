<?php

declare(strict_types=1);

namespace Serixa\Ollama\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Ollama\FakeOllamaClient;
use Serixa\Ollama\OllamaLanguageModel;

final class OllamaLanguageModelTest extends TestCase
{
    public function testFakeRoundTrip(): void
    {
        $model = OllamaLanguageModel::make(new FakeOllamaClient());
        $response = $model->complete('hello');
        self::assertSame('ollama: hello', $response->content);
        self::assertSame('llama3.2', $response->model);
    }
}

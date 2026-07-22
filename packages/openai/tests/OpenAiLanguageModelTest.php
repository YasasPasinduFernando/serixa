<?php

declare(strict_types=1);

namespace Serixa\Openai\Tests;

use PHPUnit\Framework\TestCase;
use Serixa\Openai\ChatMessage;
use Serixa\Openai\EchoLanguageModel;
use Serixa\Openai\FakeOpenAiClient;
use Serixa\Openai\OpenAiLanguageModel;

final class OpenAiLanguageModelTest extends TestCase
{
    public function testEchoModel(): void
    {
        $model = new EchoLanguageModel();
        $response = $model->complete('hello');
        self::assertSame('echo: hello', $response->content);
        self::assertFalse($response->isEmpty());
    }

    public function testFakeClientRoundTrip(): void
    {
        $model = OpenAiLanguageModel::make(new FakeOpenAiClient());
        $response = $model->chat([
            ChatMessage::system('You are helpful.'),
            ChatMessage::user('ping'),
        ]);
        self::assertSame('openai: ping', $response->content);
        self::assertSame('gpt-4o-mini', $response->model);
    }
}

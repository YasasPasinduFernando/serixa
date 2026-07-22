<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Gemini\FakeGeminiClient;
use Serixa\Gemini\GeminiLanguageModel;

$model = GeminiLanguageModel::make(new FakeGeminiClient());
$response = $model->complete('Serixa');
echo $response->content === 'gemini: Serixa' ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($response->content === 'gemini: Serixa' ? 0 : 1);

<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Ollama\FakeOllamaClient;
use Serixa\Ollama\OllamaLanguageModel;

$model = OllamaLanguageModel::make(new FakeOllamaClient());
$response = $model->complete('Serixa');
echo $response->content === 'ollama: Serixa' ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($response->content === 'ollama: Serixa' ? 0 : 1);

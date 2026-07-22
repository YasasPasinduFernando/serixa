<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Openai\FakeOpenAiClient;
use Serixa\Openai\OpenAiLanguageModel;

$model = OpenAiLanguageModel::make(new FakeOpenAiClient());
$response = $model->complete('Serixa');
echo $response->content, PHP_EOL;
echo $response->content === 'openai: Serixa' ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($response->content === 'openai: Serixa' ? 0 : 1);

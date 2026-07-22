<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Deepseek\DeepSeekLanguageModel;
use Serixa\Deepseek\FakeDeepSeekClient;

$model = DeepSeekLanguageModel::make(new FakeDeepSeekClient());
$response = $model->complete('Serixa');
echo $response->content === 'deepseek: Serixa' ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($response->content === 'deepseek: Serixa' ? 0 : 1);

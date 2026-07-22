<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Anthropic\AnthropicLanguageModel;
use Serixa\Anthropic\FakeAnthropicClient;

$model = AnthropicLanguageModel::make(new FakeAnthropicClient());
$response = $model->complete('Serixa');
echo $response->content === 'anthropic: Serixa' ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($response->content === 'anthropic: Serixa' ? 0 : 1);

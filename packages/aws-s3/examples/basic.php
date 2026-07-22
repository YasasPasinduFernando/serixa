<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\AwsS3\FakeS3Client;
use Serixa\AwsS3\S3Storage;

$s = S3Storage::make(new FakeS3Client(), 'demo');
$s->put('x.txt', 's3');
echo $s->get('x.txt'), PHP_EOL;
echo $s->url('x.txt'), PHP_EOL;
echo $s->exists('x.txt') ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($s->exists('x.txt') ? 0 : 1);

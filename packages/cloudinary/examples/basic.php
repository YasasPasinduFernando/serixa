<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Serixa\Cloudinary\CloudinaryStorage;
use Serixa\Cloudinary\FakeCloudinaryClient;

$s = CloudinaryStorage::make(new FakeCloudinaryClient());
$s->put('img.jpg', 'bytes');
echo $s->url('img.jpg'), PHP_EOL;
echo $s->exists('img.jpg') ? "SMOKE OK\n" : "SMOKE FAIL\n";
exit($s->exists('img.jpg') ? 0 : 1);

<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new \Phial\Phial(dirname(__DIR__), getenv('APP_ENV')?:'dev');

$app->run();

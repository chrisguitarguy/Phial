<?php

require __DIR__ . '/../vendor/autoload.php';

$app = new \Phial\Phial(dirname(__DIR__), getenv('APP_ENV')?:'dev');

$app->get('/', function(\Silex\Application $app) {
    var_dump($app);
    die;
});

$app->run();

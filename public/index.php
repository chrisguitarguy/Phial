<?php
/**
 * This is an example of how you might run the application.
 */

require __DIR__ . '/../vendor/autoload.php';

$app = new \Phial\Phial(dirname(__DIR__), getenv('APP_ENV')?:'dev');

$app->run();

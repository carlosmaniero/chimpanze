<?php
require_once 'autoload.php';

$app = new \Slim\Slim();
$app->get('/hello/:name', function ($name) use ($settings) {
    echo "Hello, $name";
});
$app->run();

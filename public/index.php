<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once  __DIR__ . '/../bootstrap/app.php';
$app->add('Authentication');
$app->add('User');
$app->add('Route', false);
$app->run();

<?php

use Dotenv\Dotenv;
use Hasdemir\Base\App;

error_reporting(E_ERROR | E_PARSE);

Dotenv::createImmutable(dirname(dirname(__FILE__)))->load();

$config = [
  'ROOT' => dirname(dirname(__FILE__)),
  'APP_NAME' => $_ENV['APP_NAME'],
  'MYSQL_HOST' => $_ENV['MYSQL_HOST'],
  'MYSQL_PORT' => $_ENV['MYSQL_PORT'],
  'MYSQL_NAME' => $_ENV['MYSQL_NAME'],
  'MYSQL_USER' => $_ENV['MYSQL_USER'],
  'MYSQL_PASSWORD' => $_ENV['MYSQL_PASSWORD'],
];

return new App($config);

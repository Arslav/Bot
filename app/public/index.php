<?php

/** @var ContainerInterface $container */

use Arslav\Newbot\App;
use Psr\Container\ContainerInterface;

$container = require __DIR__ . '/../bootstrap.php';

$app = new App($container);
$app->run();
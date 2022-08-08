<?php

use Arslav\Newbot\App;

$container = require __DIR__ . '/../bootstrap.php';

$app = new App($container);
$app->run();

<?php

use Arslav\Newbot\App;

$_ENV['DB_NAME']='test';

$container = require_once __DIR__ . '/../bootstrap.php';

return new App($container);

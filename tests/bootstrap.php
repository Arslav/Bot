<?php

use Arslav\Bot\App;
use Codeception\Stub;
use DI\Container;

$container = new Container();

Stub::construct(App::class, [$container], [
    'execute' => function () {},
    'getName' => 'Stub',
]);


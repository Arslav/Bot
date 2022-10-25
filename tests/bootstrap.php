<?php

use Arslav\Bot\BaseApp;
use Codeception\Stub;
use DI\Container;

$container = new Container();

Stub::construct(BaseApp::class, [$container], [
    'execute' => function () {},
    'getName' => 'Stub',
]);


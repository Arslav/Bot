<?php

use Arslav\Newbot\Services\CommandStats;
use Psr\Container\ContainerInterface as Container;
use function DI\factory;

return [
    CommandStats::class => Factory(fn (Container $c) => new CommandStats()),
];

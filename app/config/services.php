<?php

use Arslav\Newbot\Services\CommandStats;
use Psr\Container\ContainerInterface;
use function DI\factory;

/** @var ContainerInterface $c */
return [
    CommandStats::class => Factory(fn ($c) => new CommandStats()),
];

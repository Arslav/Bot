<?php

namespace Arslav\Bot\Discord\Cli;

use Throwable;
use Arslav\Bot\Cli\Command;
use Arslav\Bot\Discord\App;
use Arslav\Bot\Cli\App as Cli;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

/**
 * Class RunCommand
 *
 * @package Arslav\Bot\Discord\Cli
 */
class RunCommand extends Command
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Throwable
     */
    protected function execute(): void
    {
        $discord = $this->createApp();
        $discord->run();
    }

    /**
     * @return App
     */
    protected function createApp(): App
    {
        $container = Cli::getContainer();
        return new App($container);
    }
}

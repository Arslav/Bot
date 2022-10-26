<?php

namespace Arslav\Bot\Discord\Cli;

use Throwable;
use Arslav\Bot\Cli\Command;
use Arslav\Bot\Discord\App;
use Arslav\Bot\Cli\App as CliApp;

class RunCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function execute(): void
    {
        $container = CliApp::getContainer();
        $discord = new App($container);
        $discord->run();
    }
}

<?php

namespace Arslav\Newbot\Commands\Cli;

use Arslav\Newbot\Commands\Cli\Base\CliCommand;

class EchoCommand extends CliCommand
{
    /**
     * @return void
     */
    public function run(): void
    {
        echo $this->args[0].PHP_EOL;
    }
}
<?php

namespace Arslav\Newbot\Commands\Cli;

use Arslav\Newbot\Commands\Cli\Base\CliCommand;

class EchoCommand extends CliCommand
{
    public function run(): int
    {
        echo $this->args[1].PHP_EOL;
        return 0;
    }
}
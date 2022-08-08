<?php

namespace Arslav\Newbot\Commands\Cli;

use Arslav\Newbot\Commands\Cli\Base\CliCommand;

/**
 * Команда выводящая первый из переданных аргументов
 */
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

<?php

namespace Arslav\Newbot\Commands\Cli\Base;

use Arslav\Newbot\Commands\Command;

abstract class CliCommand extends Command
{
    /**
     * @return void
     */
    abstract public function run(): void;
}
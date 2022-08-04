<?php

namespace Arslav\Newbot\Commands\Cli\Base;

use Arslav\Newbot\App;
use Arslav\Newbot\Commands\Command;

abstract class CliCommand extends Command
{
    /**
     * @return void
     */
    abstract public function run(): void;
}
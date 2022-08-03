<?php

namespace Arslav\Newbot\Commands\Cli\Base;

use Arslav\Newbot\App;
use Arslav\Newbot\Commands\Command;

abstract class CliCommand extends Command
{
    /**
     * CliCommand constructor.
     * @param array $aliases
     */
    public function __construct(array $aliases)
    {
        $this->args = App::getArgs();
        array_shift($this->args);
        parent::__construct($aliases);
    }

    /**
     * @return int
     */
    abstract public function run(): int;
}
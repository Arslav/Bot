<?php

namespace Arslav\Bot;


abstract class BaseCommand
{
    public array $aliases = [];
    public array $args = [];

    /**
     * @return bool
     */
    public function beforeAction(): bool
    {
        return true;
    }

    /**
     * AbstractBaseCommand constructor.
     *
     * @param array $aliases
     */
    public function __construct(array $aliases)
    {
        $this->aliases = $aliases;
    }

    /**
     * @param array $args
     *
     * @return void
     */
    public function setArgs(array $args)
    {
        $this->args = $args;
    }

    /**
     * @return void
     */
    abstract public function run(): void;
}

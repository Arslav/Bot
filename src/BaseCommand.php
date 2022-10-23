<?php

namespace Arslav\Bot;


use InvalidArgumentException;

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
     * @param string|array $aliases
     */
    public function __construct(string|array $aliases)
    {
        switch (true) {
            case is_array($aliases): $this->aliases = $aliases; break;
            case is_string($aliases): $this->aliases[] = $aliases; break;
            default: throw new InvalidArgumentException('Command aliases must be string or array of string');
        }
    }

    /**
     * @param array $args
     *
     * @return void
     */
    public function setArgs(array $args): void
    {
        $this->args = $args;
    }

    /**
     * @param mixed $data
     *
     * @return void
     */
    public function init(mixed $data): void
    {
        //Stub
    }

    /**
     * @return void
     */
    abstract public function run(): void;
}

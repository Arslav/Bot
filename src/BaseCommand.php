<?php

namespace Arslav\Bot;

use InvalidArgumentException;

abstract class BaseCommand
{
    protected array $aliases = [];
    protected array $args = [];

    /**
     * @return bool
     */
    protected function beforeAction(): bool
    {
        return true;
    }

    /**
     * @return void
     */
    abstract protected function execute(): void;

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
            // @codeCoverageIgnoreStart
            default: throw new InvalidArgumentException('Command aliases must be string or array of string');
            // @codeCoverageIgnoreEnd
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
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    /**
     * @param mixed $data
     *
     * @return void
     */
    protected function init(mixed $data = null): void
    {
        //Stub
    }

    /**
     * @param mixed|null $data
     * @param array      $args
     *
     * @return void
     */
    public function run(mixed $data = null, array $args = []): void
    {
        $this->init($data);
        $this->setArgs($args);
        if ($this->beforeAction()) {
            $this->execute();
        }
    }
}

<?php

namespace Arslav\Newbot\Commands\Cli\Base;

use Arslav\Newbot\Commands\Command;
use ReflectionClass;

/**
 * @C
 */
abstract class CliCommand extends Command
{
    /**
     * @return void
     */
    abstract public function run(): void;

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        $reflectionClass = new ReflectionClass(static::class);
        $comment = $reflectionClass->getDocComment();

        preg_match('/^\s*\*\s([\w\s]*)$/mu', $comment, $matches);

        return $matches[1] ?? null;
    }
}

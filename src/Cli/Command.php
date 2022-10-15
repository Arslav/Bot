<?php

namespace Arslav\Bot\Cli;

use Arslav\Bot\BaseCommand;
use ReflectionClass;

abstract class Command extends BaseCommand
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

<?php

namespace Arslav\Bot\Cli;

use Arslav\Bot\BaseCommand;
use JetBrains\PhpStorm\Pure;
use Arslav\Bot\Helpers\AnnotationReader;

abstract class Command extends BaseCommand
{
    protected AnnotationReader $annotationReader;

    /**
     * @param array $aliases
     */
    #[Pure] public function __construct(array $aliases)
    {
        $this->annotationReader = (new AnnotationReader(static::class));
        parent::__construct($aliases);
    }

    /**
     * @return void
     */
    abstract public function run(): void;

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->annotationReader->getValue();
    }
}

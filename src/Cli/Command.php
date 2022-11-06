<?php

namespace Arslav\Bot\Cli;

use Arslav\Bot\Command as BaseCommand;
use Arslav\Bot\Helpers\AnnotationReader;
use Arslav\Bot\Command\DescriptionInterface;

/**
 * Class Command
 *
 * @package Arslav\Bot\Cli
 */
abstract class Command extends BaseCommand implements DescriptionInterface
{
    protected AnnotationReader $annotationReader;

    /**
     * @param array|string $aliases
     */
    public function __construct(string|array $aliases)
    {
        $this->annotationReader = (new AnnotationReader(static::class));
        parent::__construct($aliases);
    }

    /**
     * @return void
     */
    abstract protected function execute(): void;

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->annotationReader->getValue();
    }
}

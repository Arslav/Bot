<?php

namespace Arslav\Bot\Helpers;

use ReflectionClass;
use ReflectionException;

class AnnotationReader
{
    protected string $className;

    /**
     * @param string $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        try {
            $reflectionClass = new ReflectionClass($this->className);
            $comment = $reflectionClass->getDocComment();

            preg_match('/^\s*\*\s([\w\s]*)$/mu', $comment, $matches);

            return $matches[1] ?? null;
        } catch (ReflectionException) {
            return null;
        }
    }
}

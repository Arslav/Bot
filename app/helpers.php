<?php

use Arslav\Newbot\App;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;

/**
 * @param array $collection
 *
 * @return mixed
 */
function randomSelect(array $collection): mixed
{
    $key = array_rand($collection);

    return $collection[$key];
}

/**
 * @param string $dir
 *
 * @return array
 *
 * @throws ContainerExceptionInterface
 * @throws NotFoundExceptionInterface
 */
function getFiles(string $dir): array
{
    $files = [];
    try {
        $finder = new Finder();

        $finder->files()->in(__DIR__ . $dir);

        foreach ($finder as $file) {
            $files[] = $file->getPathname();
        }
    } catch (DirectoryNotFoundException $exception) {
        App::getLogger()->error($exception->getMessage());
    }

    return $files;
}

/**
 * @param float $chance
 *
 * @return bool
 */
function checkChance(float $chance): bool
{
    return rand(0,100) < $chance * 100;
}

/**
 * @param string $string
 * @param bool $capitalizeFirstCharacter
 *
 * @return string
 */
function snakeToCamelCase(string $string, bool $capitalizeFirstCharacter = true): string
{
    $str = str_replace('_', '', ucwords($string, '_'));
    if (!$capitalizeFirstCharacter) {
        $str = lcfirst($str);
    }

    return $str;
}


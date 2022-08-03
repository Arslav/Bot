<?php

use Arslav\Newbot\App;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;

/**
 * @param array $collection
 * @return mixed
 */
function randomSelect(array $collection)
{
    $key = array_rand($collection);
    return $collection[$key];
}

/**
 * @param string $dir
 * @return array
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
 * @return bool
 */
function checkChance(float $chance): bool
{
    return rand(0,100) < $chance*100;
}

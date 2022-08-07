<?php

namespace Tests\Support\Fixture;

use Arslav\Newbot\Entities\CommandLog;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FileFixture implements FixtureInterface
{
    public const PATH = __DIR__ . '/../Data/Fixtures/';

    protected string $fileName;

    public string $className;

    /**
     * @param string $fileName
     * @param string $className
     */
    public function __construct(string $className, string $fileName)
    {
        $this->className = $className;
        $this->fileName = $fileName;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $data = require(self::PATH . $this->fileName);
        foreach ($data as $row) {
            $object = new $this->className;
            foreach ($row as $column => $value) {
                $method = 'set' . snakeToCamelCase($column);
                $object->$method($value);
            }
            $manager->persist($object);
        }
        $manager->flush();
    }
}
<?php

namespace Arslav\Bot;

use DI\Container;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

abstract class BaseApp
{
    protected static ContainerInterface $container;
    protected static BaseApp $instance;

    /**
     * @return BaseApp
     */
    public static function getInstance(): BaseApp
    {
        return self::$instance;
    }

    /**
     * @return LoggerInterface
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public static function getLogger(): LoggerInterface
    {
        return self::getContainer()->get(LoggerInterface::class);
    }

    /**
     * @return EntityManager
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public static function getEntityManager() : EntityManager
    {
        return self::getContainer()->get(EntityManager::class);
    }

    /**
     * @return ContainerInterface|Container
     */
    public static function getContainer(): ContainerInterface|Container
    {
        return self::$container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        self::$container = $container;
        self::$instance = $this;
    }

    abstract public function run(): void;

    /**
     * @param string $alias
     *
     * @return string
     */
    protected function getRegex(string $alias): string
    {
        //TODO: Подумать на тему префиксов
        //TODO: Подумать на тему ограничения колва аргументов <args:3> <args:*>...
        $regex = str_replace('<args>', '(?<args>.*)', $alias);

        return "/$regex/ui";
    }
}

<?php

namespace Arslav\Bot;

use Throwable;
use DI\Container;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

/**
 * Class App
 *
 * @package Arslav\Bot
 */
abstract class App
{
    protected static ContainerInterface $container;
    protected static App $instance;

    /**
     * @return App
     */
    public static function getInstance(): App
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

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Throwable
     */
    public function run(): void
    {
        self::getLogger()->info('App started');
        self::getLogger()->info('Launched: ' . static::class);
        try {
            $this->execute();
        } catch (Throwable $e) {
            self::getLogger()->error($e->getMessage(), $e->getTrace());
            throw $e;
        } finally {
            App::getLogger()->info('App end');
        }
    }

    /**
     * @return void
     */
    abstract protected function execute(): void;

    /**
     * @return Bot
     */
    abstract public static function bot(): Bot;

    /**
     * @param string $alias
     * @param string $text
     * @param array  $args
     *
     * @return bool
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function checkAlias(string $alias, string $text, array &$args): bool
    {
        //TODO: Подумать на тему префиксов
        //TODO: Подумать на тему ограничения кол-ва аргументов <args:3> <args:*>...
        $regex = str_replace('<args>', '(?<args>.*)', $alias);
        $regex = "/$regex/ui";

        $result = preg_match($regex, $text, $matches);

        $args = [];
        if (isset($matches['args'])) {
            $args = explode(' ', $matches['args']);
        }
        self::getLogger()->debug('Parsed Args: ' . print_r($result, true));

        return (bool) $result;
    }
}

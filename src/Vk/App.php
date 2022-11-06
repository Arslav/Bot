<?php

namespace Arslav\Bot\Vk;

use Exception;
use DI\NotFoundException;
use DI\DependencyException;
use Arslav\Bot\Command;
use Arslav\Bot\App as BaseApp;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

/**
 * Class App
 *
 * @package Arslav\Bot\Vk
 */
class App extends BaseApp
{
    /**
     * @return Bot
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function bot(): Bot
    {
        return self::getContainer()->get(Bot::class);
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    protected function execute(): void
    {
        $message = self::bot()->getMessage();
        if ($message == null) {
            self::getLogger()->info('No data received');
            return;
        }

        /** @var Command[] $commands */
        $commands = self::getContainer()->get('vk-commands');
        foreach ($commands as $command) {
            foreach ($command->getAliases() as $alias) {
                $args = [];
                if ($this->checkAlias($alias, $message->getContent(), $args)) {
                    $command->run($message, $args);
                    return;
                }
            }
        }
    }
}

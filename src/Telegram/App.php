<?php

namespace Arslav\Bot\Telegram;

use Arslav\Bot\Command;
use DI\NotFoundException;
use DI\DependencyException;
use Arslav\Bot\App as BaseApp;
use TelegramBot\Api\Types\Update;
use TelegramBot\Api\InvalidJsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class App
 *
 * @package Arslav\Bot\Telegram
 */
class App extends BaseApp
{
    /**
     * @return Bot
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function bot(): Bot
    {
        return self::$container->get(Bot::class);
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws InvalidJsonException
     */
    protected function execute(): void
    {
        $telegramClient = self::bot()->getTelegramClient();
        $commands = self::getContainer()->get('telegram-commands');

        $telegramClient->on(function (Update $update) use ($commands) {
            self::bot()->update($update);
            $message = self::bot()->getMessage();

            /** @var Command $command */
            foreach ($commands as $command) {
                foreach ($command->getAliases() as $alias) {
                    $args = [];
                    if ($this->checkAlias($alias, $message->getContent(), $args)) {
                        $command->run($message, $args);
                        return;
                    }
                }
            }
        }, fn () => true);

        $telegramClient->run();
    }
}

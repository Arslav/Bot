<?php

namespace Arslav\Bot\Telegram;

use Arslav\Bot\BaseApp;
use DI\NotFoundException;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use DI\DependencyException;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\InvalidJsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class App extends BaseApp
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Telegram';
    }

    /**
     * @return BotApi
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getTelegram(): BotApi
    {
        return self::$container->get(BotApi::class);
    }

    /**
     * @return Client
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getTelegramClient(): Client
    {
        return self::$container->get(Client::class);
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws InvalidJsonException
     */
    protected function onStart(): void
    {
        $telegramClient = self::getTelegramClient();
        $commands = self::getContainer()->get('telegram-commands');
        /** @var Command $command */
        foreach ($commands as $command) {
            foreach ($command->aliases as $alias) {
                $commandText = $this->getCommandName($alias);
                $telegramClient->command($commandText, function (Message $message) use ($alias, $command) {
                    $args = [];
                    $this->checkAlias($alias, $message->getText(), $args);
                    $this->runCommand($command, $message, $args);
                });
            }
        }
        $telegramClient->run();
    }

    /**
     * @param string $alias
     *
     * @return string
     */
    protected function getCommandName(string $alias): string
    {
        return explode(' ', $alias)[0];
    }
}

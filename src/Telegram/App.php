<?php

namespace Arslav\Bot\Telegram;

use Arslav\Bot\BaseApp;
use DI\NotFoundException;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use DI\DependencyException;
use TelegramBot\Api\Types\Update;
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
    protected function execute(): void
    {
        $telegramClient = self::getTelegramClient();
        $commands = self::getContainer()->get('telegram-commands');

        $telegramClient->on(function (Update $update) use ($commands) {
            /** @var Command $command */
            foreach ($commands as $command) {
                foreach ($command->getAliases() as $alias) {
                    $args = [];
                    if ($this->checkAlias($alias, $update->getMessage()->getText(), $args)) {
                        $command->run($update, $args);
                        return;
                    }
                }
            }
        }, fn () => true);

        $telegramClient->run();
    }
}

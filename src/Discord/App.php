<?php

namespace Arslav\Bot\Discord;

use Discord\Discord;
use DI\NotFoundException;
use DI\DependencyException;
use Arslav\Bot\Command;
use Discord\WebSockets\Event;
use Arslav\Bot\App as BaseApp;
use Discord\Parts\Channel\Message;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

/**
 * Class App
 *
 * @package Arslav\Bot\Discord
 */
class App extends BaseApp
{
    /**
     * @return Discord
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getDiscord(): Discord
    {
        return self::getContainer()->get(Discord::class);
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    protected function execute(): void
    {
        $discord = self::getDiscord();
        /** @var Command[] $commands */
        $commands = self::getContainer()->get('discord-commands');

        $discord->on(Event::READY, function (Discord $discord) use ($commands) {
            $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) use ($commands) {

                // @codeCoverageIgnoreStart
                if ($message->author->bot) {
                    return;
                }
                // @codeCoverageIgnoreEnd

                foreach ($commands as $command) {
                    foreach ($command->getAliases() as $alias) {
                        $args = [];
                        if ($this->checkAlias($alias, $message->content, $args)) {
                            //TODO: Починить дискорд
                            //$command->run(['message' => $message, 'discord' => $discord], $args);
                            return;
                        }
                    }
                }
            });
        });

        $discord->run();
    }
}

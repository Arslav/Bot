<?php

namespace Arslav\Bot\Discord;

use Discord\Discord;
use Arslav\Bot\BaseApp;
use DI\NotFoundException;
use DI\DependencyException;
use Discord\WebSockets\Event;
use Discord\Parts\Channel\Message;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

class App extends BaseApp
{
    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'Discord';
    }

    /**
     * @return Discord
     * @throws DependencyException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getDiscord(): Discord
    {
        return self::getContainer()->get(Discord::class);
    }

    /**
     * @inheritDoc
     */
    protected function execute(): void
    {
        $discord = self::getDiscord();
        /** @var Command[] $commands */
        $commands = self::getContainer()->get('discord-commands');

        $discord->on(Event::READY, function (Discord $discord) use ($commands) {
            $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) use ($commands) {
                if ($message->author->bot) {
                    return;
                }
                foreach ($commands as $command) {
                    foreach ($command->getAliases() as $alias) {
                        $args = [];
                        if ($this->checkAlias($alias, $message->content, $args)) {
                            $command->run(['message' => $message, 'discord' => $discord], $args);
                            return;
                        }
                    }
                }
            });
        });

        $discord->run();
    }
}

<?php

namespace Arslav\Bot\Tests\Factories;

use Discord\Discord;
use Codeception\Stub;
use ReflectionException;
use Discord\Parts\User\User;
use Discord\WebSockets\Event;
use Discord\Repository\UserRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Arslav\Bot\Tests\Helpers\DiscordBotHelper;

/**
 * Class DiscordApiMockFactory
 *
 * @package Arslav\Bot\Tests\Factories
 */
class DiscordApiMockFactory
{
    public static array $events;
    public static Discord $discord;

    /**
     * @return MockObject|Discord
     * @throws ReflectionException
     */
    public static function createDiscord(): MockObject|Discord
    {
        self::$discord = Stub::makeEmpty(Discord::class, [
            'on' => function (string $event, callable $callable) {
                self::$events[$event] = $callable;
            },
            'run' => function () {
                self::$events[Event::READY](self::$discord);
                $message = DiscordBotHelper::getDiscordMessageDate();
                if ($message) {
                    self::$events[Event::MESSAGE_CREATE]($message, self::$discord);
                }
            }
        ]);

        return self::$discord;
    }
}

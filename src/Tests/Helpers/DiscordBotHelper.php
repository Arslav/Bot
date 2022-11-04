<?php

namespace Arslav\Bot\Tests\Helpers;

use Exception;
use Throwable;
use Discord\Discord;
use Codeception\Stub;
use Codeception\Module;
use Arslav\Bot\BaseApp;
use Arslav\Bot\Discord\App;
use Discord\Parts\User\User;
use Discord\Parts\Channel\Message;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

/**
 * Class DiscordBotHelper
 *
 * @package Arslav\Bot\Tests\Helpers
 */
class DiscordBotHelper extends Module
{
    public static ?string $message = null;

    /**
     * @return void
     */
    public static function clearDiscordData(): void
    {
        self::$message = null;
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Throwable
     */
    public static function waitDiscordResponse(): void
    {
        (new App(BaseApp::getContainer()))->run();
    }

    /**
     * @return Message|null
     * @throws Exception
     */
    public static function getDiscordMessageDate(): ?Message
    {
        if (!self::$message) {
            return null;
        }

        $author = self::getAuthor();
        $result = Stub::make(Message::class, ['getAuthorAttribute' => fn() => $author]);
        $result->content = self::$message;
        $result->author = $author;

        return $result;
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public static function sendDiscordMessage(string $message): void
    {
        self::$message = $message;
    }

    /**
     * @return User|MockObject
     * @throws Exception
     */
    protected static function getAuthor(): User|MockObject
    {
        $author = Stub::make(User::class);
        $author->bot = false;

        return $author;
    }
}

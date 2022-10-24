<?php

namespace Arslav\Bot\Tests\Helpers;

use Throwable;
use Arslav\Bot\BaseApp;
use Codeception\Module;
use Arslav\Bot\Telegram\App;
use TelegramBot\Api\Types\Chat;
use TelegramBot\Api\Types\Update;
use TelegramBot\Api\Types\Message;
use Psr\Container\NotFoundExceptionInterface;
use TelegramBot\Api\InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;

class TelegramBotHelper extends Module
{
    public static ?string $message = null;

    public static function sendTelegramMessage(string $message): void
    {
        self::$message = $message;
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Throwable
     */
    public function waitTelegramResponse(): void
    {
        (new App(BaseApp::getContainer()))->run();
    }

    /**
     * @return ?Update
     * @throws InvalidArgumentException
     */
    public static function getTelegramMessageData(): ?Update
    {
        if (!self::$message) {
            return null;
        }
        $message = new Message();
        $message->setText(self::$message);

        $chat = new Chat();
        $chat->setId(1);
        $message->setChat($chat);

        $update = new Update();
        $update->setMessage($message);
        return $update;
    }

    /**
     * @return void
     */
    public static function clearTelegramData(): void
    {
        self::$message = null;
    }
}

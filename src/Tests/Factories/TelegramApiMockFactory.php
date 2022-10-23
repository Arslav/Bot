<?php

namespace Arslav\Bot\Tests\Factories;

use Closure;
use Codeception\Stub;
use ReflectionException;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use PHPUnit\Framework\MockObject\MockObject;
use Arslav\Bot\Tests\Helpers\TelegramBotHelper;

class TelegramApiMockFactory
{
    public static array $commands;

    /**
     * @return MockObject|Client
     * @throws ReflectionException
     */
    public static function createApi(): MockObject|BotApi
    {
        return Stub::constructEmpty(BotApi::class, [null], []);
    }

    /**
     * @return MockObject|Client
     * @throws ReflectionException
     */
    public static function createClient(): MockObject|Client
    {
        self::$commands = [];
        TelegramBotHelper::clearTelegramData();
        return Stub::constructEmpty(Client::class, [null], [
            'command' => function (string $command, Closure $closure) {
                self::$commands[$command] = $closure;
            },
            'run' => function () {
                if (!TelegramBotHelper::$message) {
                    return;
                }
                $command = explode(' ', TelegramBotHelper::$message)[0];
                if (!in_array($command, array_keys(self::$commands))) {
                    return;
                }
                self::$commands[$command](TelegramBotHelper::getTelegramMessageData());
            }
        ]);
    }
}

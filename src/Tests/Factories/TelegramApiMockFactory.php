<?php

namespace Arslav\Bot\Tests\Factories;

use Closure;
use Codeception\Stub;
use ReflectionException;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use PHPUnit\Framework\MockObject\MockObject;
use Arslav\Bot\Tests\Helpers\TelegramBotHelper;

/**
 * Class TelegramApiMockFactory
 *
 * @package Arslav\Bot\Tests\Factories
 */
class TelegramApiMockFactory
{
    public static Closure $closure;

    /**
     * @return MockObject|BotApi
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
        TelegramBotHelper::clearTelegramData();
        return Stub::constructEmpty(Client::class, [null], [
            'on' => function ($closure) {
                self::$closure = $closure;
            },
            'run' => function () {
               $closure = self::$closure;
               $data = TelegramBotHelper::getTelegramMessageData();
               if ($data) {
                   $closure($data);
               }
            }
        ]);
    }
}

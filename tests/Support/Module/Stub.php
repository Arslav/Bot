<?php

namespace Tests\Support\Module;

use Discord\Discord;
use Arslav\Bot\App;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use Arslav\Bot\Tests\Factories\VkApiMockFactory;
use Codeception\Module;
use Codeception\Stub as CodeceptionStub;
use Codeception\TestInterface;
use DigitalStar\vk_api\vk_api;
use Psr\Log\LoggerInterface;
use ReflectionException;
use Arslav\Bot\Tests\Factories\DiscordApiMockFactory;
use Arslav\Bot\Tests\Factories\TelegramApiMockFactory;

class Stub extends Module
{
    /**
     * @param TestInterface $test
     *
     * @return void
     *
     * @throws ReflectionException
     */
    public function _before(TestInterface $test): void
    {
        $container = App::getContainer();
        $container->set(LoggerInterface::class, CodeceptionStub::constructEmpty(LoggerInterface::class));

        parent::_before($test);
    }
}

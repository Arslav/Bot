<?php

namespace Tests\Support\Module;

use Arslav\Bot\BaseApp;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use Arslav\Bot\Tests\Factories\VkApiMockFactory;
use Codeception\Module;
use Codeception\Stub as CodeceptionStub;
use Codeception\TestInterface;
use DigitalStar\vk_api\vk_api;
use Psr\Log\LoggerInterface;
use ReflectionException;
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
        $container = BaseApp::getContainer();
        $container->set(LoggerInterface::class, CodeceptionStub::constructEmpty(LoggerInterface::class));
        $container->set(vk_api::class, VkApiMockFactory::create());
        $container->set(BotApi::class, TelegramApiMockFactory::createApi());
        $container->set(Client::class, TelegramApiMockFactory::createClient());

        parent::_before($test);
    }
}

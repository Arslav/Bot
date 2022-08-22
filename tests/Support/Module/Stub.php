<?php

namespace Tests\Support\Module;

use Arslav\Bot\App;
use Arslav\Bot\Tests\Factories\VkApiMockFactory;
use Arslav\Bot\Tests\Helpers\VkChatHelper;
use Codeception\Module;
use Codeception\Stub as CodeceptionStub;
use Codeception\TestInterface;
use DigitalStar\vk_api\vk_api;
use Psr\Log\LoggerInterface;
use ReflectionException;

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
        $container->set(vk_api::class, VkApiMockFactory::create());

        parent::_before($test);
    }
}

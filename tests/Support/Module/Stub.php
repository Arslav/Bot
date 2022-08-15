<?php

namespace Tests\Support\Module;

use Arslav\Bot\App;
use Codeception\Module;
use Codeception\Stub as CodeceptionStub;
use Codeception\TestInterface;
use DigitalStar\vk_api\vk_api;
use Psr\Log\LoggerInterface;
use ReflectionException;
use Tests\Support\Helper\Message;

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
        $container->set(vk_api::class, CodeceptionStub::constructEmpty(
            vk_api::class,
            [null, null],
            ['initVars' => fn() => Message::getVkMessageData()],
        ));

        parent::_before($test);
    }
}

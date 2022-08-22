<?php

namespace Arslav\Bot\Tests\Factories;

use Arslav\Bot\Tests\Helpers\VkChatHelper;
use Codeception\Stub;
use DigitalStar\vk_api\vk_api;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionException;

class VkApiMockFactory
{
    /**
     * @throws ReflectionException
     */
    public static function create(): MockObject|vk_api
    {
        return Stub::constructEmpty(
            vk_api::class,
            [null, null],
            [
                'initVars' => fn() => VkChatHelper::getVkMessageData()
            ],
        );
    }
}

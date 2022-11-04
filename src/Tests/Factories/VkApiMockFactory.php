<?php

namespace Arslav\Bot\Tests\Factories;

use Arslav\Bot\Tests\Helpers\VkChatHelper;
use Codeception\Stub;
use DigitalStar\vk_api\vk_api;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionException;

/**
 * Class VkApiMockFactory
 *
 * @package Arslav\Bot\Tests\Factories
 */
class VkApiMockFactory
{
    /**
     * @throws ReflectionException
     */
    public static function create(): MockObject|vk_api
    {
        VkChatHelper::clearVkData();
        return Stub::constructEmpty(
            vk_api::class,
            [null, null],
            [
                'initVars' => fn() => VkChatHelper::getVkMessageData(),
                'reply' => function ($msg) {
                    VkChatHelper::$botMessage = $msg;
                },
                'sendImage' => function () {
                    VkChatHelper::$botImage = true;
                }
            ],
        );
    }
}

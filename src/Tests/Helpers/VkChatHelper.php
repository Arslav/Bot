<?php

declare(strict_types=1);

namespace Arslav\Bot\Tests\Helpers;

use Throwable;
use Arslav\Bot\BaseApp;
use Arslav\Bot\Vk\App;
use Codeception\Module;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use stdClass;

class VkChatHelper extends Module
{
    public static ?string $message = null;
    public static ?string $botMessage = null;
    public static bool $botImage = false;
    public static int $fromId = 1;
    public static int $peerId = 1;
    public static string $type = 'message_new';

    /**
     * @param string $message
     *
     * @return void
     */
    public function sendVkMessage(string $message): void
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
    public function waitVkResponse(): void
    {
        (new App(BaseApp::getContainer()))->run();
    }

    /**
     * @return string|null
     */
    public function grabVkBotMessage(): ?string
    {
        return self::$botMessage;
    }


    /**
     * @return void
     */
    public function seeVkBotImage(): void
    {
        $this->assertTrue(self::$botImage);
    }

    /**
     * @return ?stdClass
     */
    public static function getVkMessageData(): ?stdClass
    {
        if (!self::$message) {
            return null;
        }
        //TODO: Переписать на DTO
        $dataArray = [
            'object' => [
                'peer_id' => self::$fromId,
                'text' => self::$message,
                'payload' => [],
                'from_id' => self::$peerId,
            ],
            'type' => self::$type
        ];
        return json_decode(json_encode($dataArray), false);
    }

    /**
     * @return void
     */
    public static function clearVkData(): void
    {
        self::$message = null;
        self::$botMessage = null;
        self::$botImage = false;
        self::$fromId = 1;
        self::$peerId = 1;
        self::$type = 'message_new';
    }
}

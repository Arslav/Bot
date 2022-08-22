<?php

declare(strict_types=1);

namespace Arslav\Bot\Tests\Helpers;

use Arslav\Bot\App;
use Codeception\Module;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use stdClass;

class VkChatHelper extends Module
{
    protected static string $message;
    protected static ?string $botMessage = null;
    public static int $from_id = 1;
    public static int $peer_id = 1;
    public static string $type = 'message_new';

    static ?stdClass $vkMessageData = null;

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
     */
    public function waitVkResponse(): void
    {
        App::getInstance()->run();
    }

    /**
     * @return string|null
     */
    public function grabVkBotMessage(): ?string
    {
        return self::$botMessage;
    }

    /**
     * @return stdClass
     */
    public static function getVkMessageData(): stdClass
    {
        //TODO: Переписать на DTO
        $dataArray = [
            'object' => [
                'peer_id' => self::$from_id,
                'text' => self::$message,
                'payload' => [],
                'from_id' => self::$peer_id,
            ],
            'type' => self::$type
        ];
        return json_decode(json_encode($dataArray), false);
    }
}

<?php

namespace Arslav\Bot\Vk;

use Arslav\Bot\BaseBot;
use Arslav\Bot\Api\Message;
use DigitalStar\vk_api\vk_api;
use DigitalStar\vk_api\VkApiException;

/**
 * Class Bot
 *
 * @package Arslav\Bot\Vk
 */
class Bot extends BaseBot
{
    protected vk_api $client;
    protected static Message $message;

    /**
     * @throws VkApiException
     */
    public function __construct(string $token, string $confirmString, string $version = '5.101')
    {
        $this->client = vk_api::create($token, $version)->setConfirm($confirmString);
    }

    /**
     * @return Message|null
     */
    public function getMessage(): ?Message
    {
        if (self::$message) {
            return self::$message;
        }

        /** @var object $data */
        $data = $this->client->initVars();

        if ($data->type != 'message_new') {
            return null;
        }

        [$userId, $chatId] = $this->getIds($data);

        self::$message = new Message(
            $data->object->text,
            $userId,
            $chatId,
            $this
        );

        return self::$message;
    }

    /**
     * @param string $message
     *
     * @return void
     * @throws VkApiException
     */
    public function reply(string $message): void
    {
        $this->client->reply($message);
    }

    /**
     * @param int    $chatId
     * @param string $message
     *
     * @return void
     * @throws VkApiException
     */
    public function send(int $chatId, string $message): void
    {
        $this->client->sendMessage($chatId, $message);
    }

    /**
     * @param int    $chatId
     * @param string $path
     *
     * @return void
     * @throws VkApiException
     */
    public function sendPhoto(int $chatId, string $path): void
    {
        $this->client->sendImage($chatId, $path);
    }

    /**
     * @param mixed $data
     *
     * @return array
     */
    protected function getIds(mixed $data): array
    {
        $userId = $data->object->peerId;
        $chatId = null;

        if ($data->object->peerId - 2000000000 > 0) {
            $chatId = $data->object->peer_id;
            $userId = $data->object->from_id;
        }

        return [$userId, $chatId];
    }
}

<?php

namespace Arslav\Bot\Telegram;

use CURLFile;
use Arslav\Bot\Api\Message;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\Exception;
use Arslav\Bot\Bot as BaseBot;
use TelegramBot\Api\Types\Update;
use TelegramBot\Api\InvalidArgumentException;

/**
 * Class Bot
 *
 * @package Arslav\Bot\Telegram
 */
class Bot extends BaseBot
{
    protected BotApi $botApiClient;
    protected Client $client;

    protected static ?Message $message = null;

    /**
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->client = new Client($token);
        $this->botApiClient = new BotApi($token);
    }

    /**
     * @param string $message
     *
     * @return void
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function reply(string $message): void
    {
        $this->botApiClient->sendMessage(self::$message->getChatId(), $message);
    }

    /**
     * @param int    $chatId
     * @param string $message
     *
     * @return void
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function send(int $chatId, string $message): void
    {
        $this->botApiClient->sendMessage($chatId, $message);
    }

    /**
     * @param int    $chatId
     * @param string $path
     *
     * @return void
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function sendPhoto(int $chatId, string $path): void
    {
        $file = new CURLFile($path);
        $this->botApiClient->sendPhoto($chatId, $file);
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): ?Message
    {
        return self::$message;
    }

    /**
     * @return Client
     */
    public function getTelegramClient(): Client
    {
        return $this->client;
    }

    /**
     * @throws Exception
     */
    public function setWebhook(string $url, string $cert = null)
    {
        $this->botApiClient->setWebhook($url, $cert);
    }

    /**
     * @param Update $update
     *
     * @return void
     */
    public function update(Update $update): void
    {
        $telegramMessage = $update->getMessage();

        self::$message = new Message(
            $telegramMessage->getText(),
            $telegramMessage->getFrom()->getId(),
            $telegramMessage->getChat()->getId(),
            $this
        );
    }
}

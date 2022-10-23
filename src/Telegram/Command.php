<?php

namespace Arslav\Bot\Telegram;

use Arslav\Bot\BaseCommand;
use TelegramBot\Api\Types\Message;

abstract class Command extends BaseCommand
{
    public Message $data;
    public string $message;
    public int $chatId;

    /**
     * @return void
     */
    abstract public function run(): void;

    /**
     * @param mixed $data
     *
     * @return void
     */
    public function init(mixed $data): void
    {
        $this->data = $data;
        $this->message = $data->getText();
        $this->chatId = (int) $data->getChat()->getId();
    }
}

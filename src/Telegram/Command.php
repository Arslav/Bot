<?php

namespace Arslav\Bot\Telegram;

use Arslav\Bot\BaseCommand;
use TelegramBot\Api\Types\Update;

abstract class Command extends BaseCommand
{
    public Update $data;
    public string $message;
    public int $chatId;

    /**
     * @return void
     */
    abstract protected function execute(): void;

    /**
     * @param mixed $data
     *
     * @return void
     */
    protected function init(mixed $data = null): void
    {
        /** @var Update $data */
        $this->data = $data;
        $this->message = $data->getMessage()->getText();
        $this->chatId = (int) $data->getMessage()->getChat()->getId();
    }
}

<?php

namespace Arslav\Bot\Vk;

use Arslav\Bot\BaseCommand;
use Arslav\Bot\Api\Message;

abstract class Command extends BaseCommand
{
    public Message $message;

    /**
     * @param mixed|null $data
     */
    protected function init(mixed $data = null) : void
    {
        /** @var Message $data */
        $this->message = $data;
    }

    /**
     * @return void
     */
    abstract protected function execute(): void;
}

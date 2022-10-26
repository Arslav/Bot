<?php

namespace Arslav\Bot\Discord;

use Discord\Discord;
use Arslav\Bot\BaseCommand;
use Discord\Parts\Channel\Message;

abstract class Command extends BaseCommand
{
    public Message $message;
    public Discord $discord;

    /**
     * @param mixed|null $data
     *
     * @return void
     */
    protected function init(mixed $data = null): void
    {
        $this->message = $data['message'];
        $this->discord = $data['discord'];
        parent::init($data);
    }

    /**
     * @inheritDoc
     */
    abstract protected function execute(): void;
}

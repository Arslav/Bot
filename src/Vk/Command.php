<?php

namespace Arslav\Bot\Vk;

use Arslav\Bot\BaseCommand;

abstract class Command extends BaseCommand
{
    public ?string $message;
    public ?int $peerId;
    public ?int $fromId;
    public ?int $chatId = null;
    public mixed $data;

    //TODO: Параметр для отключения ^ $

    /**
     * @param mixed|null $data
     */
    protected function init(mixed $data = null) : void
    {
        $this->data = $data;
        $this->message = $data->object->text;
        $this->peerId = $data->object->peer_id;
        $this->fromId = $data->object->from_id;
        //См. https://kotoff.net/article/31-vk-bot-poleznye-funkcii-komandy-dlja-bota-vk.html
        $chatId = $this->peerId - 2000000000;
        if ($chatId > 0) {
            $this->chatId = $chatId;
        }
    }

    /**
     * @return bool
     */
    public function isFromChat(): bool
    {
        //Если $chat_id > 0, то сообщение в беседе, если меньше, то ЛС.
        return (bool) $this->chatId;
    }

    /**
     * @return void
     */
    abstract protected function execute(): void;
}

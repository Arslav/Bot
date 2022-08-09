<?php

namespace Arslav\Bot\Commands\Vk\Base;

use Arslav\Bot\Commands\Command;

abstract class VkCommand extends Command
{
    /**
     * @var string
     */
    public $message;

    /**
     * @var string
     */
    public $peer_id;

    /**
     * @var string
     */
    public $from_id;

    /**
     * @var string
     */
    public $chat_id = null;

    /**
     * @var mixed
     */
    public mixed $data;

    //TODO: Параметр для отключения ^ $

    /**
     * @param mixed $data
     */
    public function init(mixed $data) : void
    {
        $this->data = $data;
        $this->message = $data->object->text;
        $this->peer_id = $data->object->peer_id;
        $this->from_id = $data->object->from_id;
        //См. https://kotoff.net/article/31-vk-bot-poleznye-funkcii-komandy-dlja-bota-vk.html
        $chatId = $this->peer_id - 2000000000;
        if ($chatId > 0) {
            $this->chat_id = $chatId;
        }
    }

    /**
     * @return bool
     */
    public function isFromChat(): bool
    {
        //Если $chat_id > 0, то сообщение в беседе, если меньше, то ЛС.
        return (bool) $this->chat_id;
    }

    /**
     * @return void
     */
    public abstract function run(): void;
}
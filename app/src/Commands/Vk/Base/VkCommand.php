<?php

namespace Arslav\Newbot\Commands\Vk\Base;

use Arslav\Newbot\App;
use Arslav\Newbot\Commands\Command;
use JetBrains\PhpStorm\Pure;

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
    public $chat_id;

    /**
     * @var mixed
     */
    public mixed $data;

    //TODO: Параметр для отключения ^ $

    /**
     * CliCommand constructor.
     *
     * @param array $aliases
     */
    #[Pure]
    public function __construct(array $aliases)
    {
        $this->args = App::getArgs();
        parent::__construct($aliases);
    }
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
        $this->chat_id = $data->object->peer_id - 2000000000;
    }

    /**
     * @return bool
     */
    public function isFromChat(): bool
    {
        //Если $chat_id > 0, то сообщение в беседе, если меньше, то ЛС.
        return $this->chat_id > 0;
    }

    /**
     * @return void
     */
    public abstract function run(): void;
}
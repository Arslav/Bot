<?php

namespace Arslav\Bot\Api;

use Arslav\Bot\BaseBot;

class User
{
    protected int $id;
    protected BaseBot $bot;

    /**
     * @param int     $id
     * @param BaseBot $bot
     */
    public function __construct(int $id, BaseBot $bot)
    {
        $this->id = $id;
        $this->bot = $bot;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}

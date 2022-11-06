<?php

namespace Arslav\Bot\Api;

use Arslav\Bot\Bot;

/**
 * Class User
 *
 * @package Arslav\Bot\Api
 */
class User
{
    protected int $id;
    protected Bot $bot;

    /**
     * @param int $id
     * @param Bot $bot
     */
    public function __construct(int $id, Bot $bot)
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

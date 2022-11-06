<?php

namespace Arslav\Bot;

use Arslav\Bot\Api\Message;

/**
 * Class BaseBot
 *
 * @package Arslav\Bot
 */
abstract class BaseBot
{
    /**
     * @param string $message
     *
     * @return void
     */
    abstract public function reply(string $message): void;

    /**
     * @param int    $chatId
     * @param string $message
     *
     * @return void
     */
    abstract public function send(int $chatId, string $message): void;

    /**
     * @param int    $chatId
     * @param string $path
     *
     * @return void
     */
    abstract public function sendPhoto(int $chatId, string $path): void;

    /**
     * @return Message|null
     */
    abstract public function getMessage(): ?Message;
}

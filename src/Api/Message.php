<?php

namespace Arslav\Bot\Api;

use Arslav\Bot\Bot;

/**
 * Class Message
 *
 * @package Arslav\Bot\Api
 */
class Message
{
    protected string $content;
    protected ?int $chatId;
    protected int $userId;
    protected Bot $bot;

    /**
     * @param string   $content
     * @param int      $userId
     * @param int|null $chatId
     * @param Bot      $bot
     */
    public function __construct(string $content, int $userId, ?int $chatId, Bot $bot)
    {
        $this->content = $content;
        $this->chatId = $chatId;
        $this->userId = $userId;
        $this->bot = $bot;
    }

    /**
     * @return null|Chat
     */
    public function getChat(): ?Chat
    {
        if (!$this->chatId) {
            return null;
        }

        return new Chat($this->chatId, $this->bot);
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        if ($this->userId) {
            return null;
        }

        return new User($this->userId, $this->bot);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return int|null
     */
    public function getChatId(): ?int
    {
        return $this->chatId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}

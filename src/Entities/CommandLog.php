<?php

namespace Arslav\Newbot\Entities;

use Arslav\Newbot\Entities\Traits\TimestampTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * @Entity
 * @HasLifecycleCallbacks
 * @Table(name="command_log")
 */
class CommandLog
{
    use TimestampTrait;
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected int $id;

    /** @Column(type="integer", nullable=false) */
    protected int $from_id;

    /** @Column(type="integer", nullable=true) */
    protected ?int $chat_id;

    /** @Column(type="string", length=255, nullable=false) */
    protected string $command;

    /**
     * @return int
     */
    public function getFromId(): int
    {
        return $this->from_id;
    }

    /**
     * @param int $from_id
     */
    public function setFromId(int $from_id): void
    {
        $this->from_id = $from_id;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    /**
     * @return int|null
     */
    public function getChatId(): ?int
    {
        return $this->chat_id;
    }

    /**
     * @param int|null $chat_id
     */
    public function setChatId(?int $chat_id): void
    {
        $this->chat_id = $chat_id;
    }

}

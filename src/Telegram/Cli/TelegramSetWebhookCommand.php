<?php

namespace Arslav\Bot\Telegram\Cli;

use Arslav\Bot\Cli\Command;
use Arslav\Bot\Telegram\App;
use TelegramBot\Api\Exception;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

/**
 * Команда для установки webhook'а на который будут приходить запросы от телеграмма
 *
 * @package Arslav\Bot\Telegram\Cli
 */
class TelegramSetWebhookCommand extends Command
{

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    protected function execute(): void
    {
        $url = $this->args[0];
        $cert = $this->args[1] ?? null;
        App::getTelegram()->setWebhook($url, $cert);
    }
}

<?php

namespace Arslav\Bot\Telegram\Cli;

use Arslav\Bot\BaseCommand;
use Arslav\Bot\Telegram\App;
use TelegramBot\Api\Exception;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

class TelegramSetWebhookCommand extends BaseCommand
{

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function run(): void
    {
        $url = $this->args[0];
        $cert = $this->args[1] ?? null;
        App::getTelegram()->setWebhook($url, $cert);
    }
}

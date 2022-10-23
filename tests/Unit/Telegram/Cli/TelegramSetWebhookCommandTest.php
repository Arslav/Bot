<?php

namespace Tests\Unit\Telegram\Cli;

use Exception;
use Arslav\Bot\BaseApp;
use Codeception\Test\Unit;
use TelegramBot\Api\BotApi;
use Codeception\Stub\Expected;
use TelegramBot\Api\Exception as TelegramBotException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use Arslav\Bot\Telegram\Cli\TelegramSetWebhookCommand;

class TelegramSetWebhookCommandTest extends Unit
{
    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws TelegramBotException
     * @throws Exception
     */
    public function testRun()
    {
        $mock = $this->make(BotApi::class, [
            'setWebhook' => Expected::once(),
        ]);
        BaseApp::getContainer()->set(BotApi::class, $mock);
        $command = new TelegramSetWebhookCommand(['test']);
        $command->setArgs(['test']);
        $command->run();
    }
}


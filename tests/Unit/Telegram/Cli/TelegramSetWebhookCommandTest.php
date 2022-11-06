<?php

namespace Tests\Unit\Telegram\Cli;

use Exception;
use Arslav\Bot\App;
use Codeception\Test\Unit;
use Arslav\Bot\Telegram\Bot;
use Codeception\Stub\Expected;
use Arslav\Bot\Telegram\Cli\SetWebhookCommand;

/**
 * Class TelegramSetWebhookCommandTest
 *
 * @package Tests\Unit\Telegram\Cli
 */
class TelegramSetWebhookCommandTest extends Unit
{
    /**
     * @return void
     * @throws Exception
     */
    public function testRun(): void
    {
        $mock = $this->make(Bot::class, [
            'setWebhook' => Expected::once(),
        ]);
        App::getContainer()->set(Bot::class, $mock);
        $command = new SetWebhookCommand(['test']);
        $command->run(null, ['test']);
    }
}


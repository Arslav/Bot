<?php

namespace Tests\Unit\Telegram;

use Throwable;
use Exception;
use Arslav\Bot\BaseApp;
use Codeception\Test\Unit;
use TelegramBot\Api\Client;
use Arslav\Bot\Telegram\App;
use Tests\Support\UnitTester;
use Codeception\Stub\Expected;
use Arslav\Bot\Telegram\Command;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

class AppTest extends Unit
{
    protected UnitTester $tester;

    /**
     * @return void
     * @throws Exception
     */
    public function testRunWithCommand(): void
    {
        $container = BaseApp::getContainer();
        $container->set('telegram-commands', [
            $this->construct(Command::class, [['test']], [
                'execute' => Expected::once()
            ])
        ]);
        $this->tester->sendTelegramMessage('test');
        $this->tester->waitTelegramResponse();
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Throwable
     */
    public function testRunWithoutCommands(): void
    {
        $container = BaseApp::getContainer();
        $container->set('telegram-commands', []);
        $app = new App($container);
        $app->run();
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetTelegram(): void
    {
        $this->assertInstanceOf(Client::class, App::getTelegramClient());
    }

    /**
     * @return void
     */
    public function testGetName(): void
    {
        $app = new App(BaseApp::getContainer());
        $this->assertSame('Telegram', $app->getName());
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetTelegramClient(): void
    {
        $this->assertInstanceOf(Client::class, App::getTelegramClient());
    }
}

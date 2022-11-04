<?php

namespace Tests\Unit\Discord;

use Exception;
use Throwable;
use Discord\Discord;
use Arslav\Bot\BaseApp;
use Codeception\Test\Unit;
use Arslav\Bot\Discord\App;
use Tests\Support\UnitTester;
use Codeception\Stub\Expected;
use Arslav\Bot\Discord\Command;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

/**
 * Class AppTest
 *
 * @package Tests\Unit\Discord
 */
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
        $container->set('discord-commands', [
            $this->construct(Command::class, [['test']], [
                'execute' => Expected::once()
            ])
        ]);
        $this->tester->sendDiscordMessage('test');
        $this->tester->waitDiscordResponse();
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
        $container->set('discord-commands', []);
        $app = new App($container);
        $app->run();
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetDiscord(): void
    {
        $this->assertInstanceOf(Discord::class, App::getDiscord());
    }

    /**
     * @return void
     */
    public function testGetName(): void
    {
        $app = new App(BaseApp::getContainer());
        $this->assertSame('Discord', $app->getName());
    }
}

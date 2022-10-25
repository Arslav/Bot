<?php

namespace Tests\Unit\Vk;

use Throwable;
use Arslav\Bot\Vk\App;
use Arslav\Bot\Vk\Command;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use DigitalStar\vk_api\vk_api;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\Support\UnitTester;

class AppTest extends Unit
{
    protected UnitTester $tester;

    /**
     * @param string $message
     * @param array $commandAliases
     * @param array $args
     *
     * @return void
     *
     * @throws Exception
     *
     * @dataProvider messageProvider
     */
    public function testSetArgs(string $message, array $commandAliases, array $args): void
    {
        $command = $this->construct(
            Command::class,
            [$commandAliases],
            ['execute' => null]
        );
        App::getContainer()->set('vk-commands', [$command]);
        $this->tester->sendVkMessage($message);
        $this->tester->waitVkResponse();
        $this->assertSame($args, $command->getArgs());
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetVk()
    {
        $this->assertInstanceOf(vk_api::class, App::getVk());
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Throwable
     */
    public function testRun(): void
    {
        $this->tester->sendVkMessage('test');
        App::getInstance()->run();
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Throwable
     */
    public function testRunWithoutMessage(): void
    {
        App::getInstance()->run();
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Throwable
     */
    public function testRunUnsupportedMessage(): void
    {
        $this->tester->sendVkMessage('test');
        $data = $this->tester->getVkMessageData();
        $data->type = 'unsupported';
        $app = $this->make(new App(App::getContainer()), [
            'init' => $data,
        ]);
        $app->run();
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testRunWithCommand(): void
    {
        App::getContainer()->set('vk-commands', [
            $this->construct(Command::class, [['test']], ['execute' => Expected::once()]),
            $this->construct(Command::class, [['test2']], ['execute' => Expected::never()]),
            $this->construct(Command::class, [['test3']], ['execute' => Expected::never()]),
        ]);
        $this->tester->sendVkMessage('test');
        $this->tester->waitVkResponse();
    }



    /**
     * @return array[]
     */
    public function messageProvider(): array
    {
        return [
            ['test', ['test'], []],
            ['some text', ['test'], []],
            ['test', ['test <args>'], []],
            ['test value1', ['test <args>'], ['value1']],
            ['command value1 value2 value3', ['command <args>'], ['value1', 'value2', 'value3']],
            ['some text', ['command <args>'], []],
        ];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function testRunWithError(): void
    {
        Stub::update(App::getLogger(), [
            'error' => Expected::once(),
        ]);
        App::getContainer()->set('vk-commands', [
            $this->construct(Command::class, [['test']], [
                'execute' => function() {
                    throw new Exception('test exception');
                }
            ]),
        ]);
        $this->tester->sendVkMessage('test');
        try {
            $this->tester->waitVkResponse();
        } catch (Exception $e) {
            $this->assertSame('test exception', $e->getMessage());
        }
    }
}

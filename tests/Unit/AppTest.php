<?php

namespace Tests\Unit;

use Arslav\Bot\App;
use Arslav\Bot\Commands\Vk\Base\VkCommand;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use DigitalStar\vk_api\vk_api;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Tests\Support\UnitTester;

class AppTest extends Unit
{
    protected UnitTester $tester;

    /**
     * @return void
     *
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @param string $message
     * @param array $commandAliases
     * @param array $args
     *
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     *
     * @dataProvider messageProvider
     */
    public function testSetArgs(string $message, array $commandAliases, array $args): void
    {
        $command = $this->construct(
            VkCommand::class,
            [$commandAliases],
            ['run' => null]
        );
        App::getContainer()->set('vk-commands', [$command]);
        $this->tester->sendVkMessage($message);
        $this->tester->waitVkResponse();
        $this->assertSame($args, $command->args);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function testGetEntityManager()
    {
        App::getContainer()->set(EntityManager::class, $this->makeEmpty(EntityManager::class));
        $this->assertInstanceOf(EntityManager::class, App::getEntityManager());
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
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testRun()
    {
        App::getInstance()->run();
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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
     * @throws Exception
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
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function testRunWithCommand(): void
    {
        App::getContainer()->set('vk-commands', [
            $this->construct(VkCommand::class, [['test']], ['run' => Expected::once()]),
            $this->construct(VkCommand::class, [['test2']], ['run' => Expected::never()]),
            $this->construct(VkCommand::class, [['test3']], ['run' => Expected::never()]),
        ]);
        $this->tester->sendVkMessage('test');
        $this->tester->waitVkResponse();
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetLogger(): void
    {
        $this->assertInstanceOf(LoggerInterface::class, App::getLogger());
    }

    /**
     * @return void
     */
    public function testGetContainer(): void
    {
        $this->assertInstanceOf(ContainerInterface::class, App::getContainer());
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
            $this->construct(VkCommand::class, [['test']], [
                'run' => function() {
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

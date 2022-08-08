<?php

namespace Tests\Unit;

use Arslav\Newbot\App;
use Arslav\Newbot\Commands\Vk\Base\VkCommand;
use Arslav\Newbot\DTO\VkDto;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use DI\Container;
use DigitalStar\vk_api\vk_api;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use stdClass;
use Tests\Support\UnitTester;

class AppTest extends Unit
{
    protected App $app;

    protected ?Container $container;

    protected UnitTester $tester;

    /**
     * @return void
     *
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->container = App::getContainer();
        $this->container->set(LoggerInterface::class, $this->constructEmpty(LoggerInterface::class));
        $this->container->set(vk_api::class, $this->constructEmpty(vk_api::class, [null, null], [
            'initVars' => function(&$id, &$message){
                $id = $this->tester->getVkMessageData()->object->peer_id;
                $message = $this->tester->getVkMessageData()->object->text;
                return $this->tester->getVkMessageData();
            }
        ]));
        $this->app = new App($this->container);
        parent::setUp();
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     *
     * @dataProvider messageProvider
     */
    public function testSetArgs(string $message, array $commandAliases, array $args)
    {
        $command = $this->construct(
            VkCommand::class,
            [$commandAliases],
            ['run' => null]
        );
        $this->container->set('vk-commands', [$command]);
        $this->tester->sendMessage($message);
        $this->app->run();
        $this->assertSame($args, $command->args);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetEntityManager()
    {
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
        $this->app->run();
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testRunWithoutMessage()
    {
        $this->container->set(vk_api::class, $this->constructEmpty(vk_api::class, [null, null], [
            'initVars' => null
        ]));
        $this->app = new App($this->container);
        $this->app->run();
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function testRunUnsupportedMessage()
    {
        $this->tester->sendMessage('test');
        $data = $this->tester->getVkMessageData();
        $data->type = 'unsupported';
        $this->app = $this->make(new App($this->container), [
            'init' => new VkDto(1, $data, 'test')
        ]);
        $this->app->run();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function testRunWithCommand()
    {
        $this->container->set('vk-commands', [
            $this->construct(VkCommand::class, [['test']], ['run' => Expected::once()]),
            $this->construct(VkCommand::class, [['test2']], ['run' => Expected::never()]),
            $this->construct(VkCommand::class, [['test3']], ['run' => Expected::never()]),
        ]);
        $this->tester->sendMessage('test');
        $this->app->run();
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetLogger()
    {
        $this->assertInstanceOf(LoggerInterface::class, App::getLogger());
    }

    /**
     * @return void
     */
    public function testGetContainer()
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
}

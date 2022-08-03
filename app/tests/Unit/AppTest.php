<?php

namespace Tests\Unit;

use Arslav\Newbot\App;
use Arslav\Newbot\Commands\Vk\Base\VkCommand;
use Arslav\Newbot\DTO\VkDto;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use ContainerBuilder;
use DigitalStar\vk_api\vk_api;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

class AppTest extends Unit
{
    protected App $app;

    protected array $data;

    protected ?\DI\Container $container;

    /**
     * @return void
     *
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->data = [
            'object' => [
                'peer_id' => 1,
                'text' => 'test',
                'payload' => [],
                'from_id' => 1,
            ],
            'type' => 'message_new'
        ];
        $this->container = ContainerBuilder::build();
        $this->container->set(LoggerInterface::class, $this->constructEmpty(LoggerInterface::class));
        $this->app = $this->make(new App($this->container), [
            'init' => new VkDto(1, (object) $this->data, 'test')
        ]);
        parent::setUp();
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     *
     * @dataProvider messageProvider
     */
    public function testGetArgs(string $message, string $command, array $args)
    {
        $this->container->set('vk-commands', [
            $this->constructEmpty(VkCommand::class, [[$command]]),
        ]);
        $this->data['text'] = $message;
        $this->app = $this->make(new App($this->container), [
            'init' => new VkDto(1, (object) $this->data, $message)
        ]);
        $this->app->run();
        $this->assertSame($args, App::getArgs());
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
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function testRunWithCommand()
    {
        $this->container->set('vk-commands', [
            $this->constructEmpty(VkCommand::class, [['test']], [
                'beforeAction' => true,
                'run' => Expected::once(),
            ]),
            $this->constructEmpty(VkCommand::class, [['test2']], [
                'run' => Expected::never(),
            ]),
            $this->constructEmpty(VkCommand::class, [['test3']], [
                'run' => Expected::never(),
            ]),
        ]);
        $this->data['text'] = 'test';
        $this->app = $this->make(new App($this->container), [
            'init' => new VkDto(1, (object) $this->data, 'test')
        ]);
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
            ['test', 'test', []],
            ['some text', 'test', []],
            ['test', 'test <args>', []],
            ['test value1', 'test <args>', ['value1']],
            ['test value1 value2 value3', 'test <args>', ['value1', 'value2', 'value3']],
            ['some text', 'test <args>', []],
        ];
    }
}

<?php

namespace Tests\Unit;

use Arslav\Newbot\App;
use Arslav\Newbot\Commands\Vk\Base\VkCommand;
use Arslav\Newbot\DTO\VkDto;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use ContainerBuilder;
use DI\Container;
use DigitalStar\vk_api\vk_api;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use stdClass;

class AppTest extends Unit
{
    protected App $app;

    protected stdClass $data;

    protected ?Container $container;

    /**
     * @return void
     *
     * @throws Exception
     */
    protected function setUp(): void
    {
        $dataArray = [
            'object' => [
                'peer_id' => 1,
                'text' => 'test',
                'payload' => [],
                'from_id' => 1,
            ],
            'type' => 'message_new'
        ];
        $this->data = json_decode(json_encode($dataArray), false);
        $this->container = ContainerBuilder::build();
        $this->container->set(LoggerInterface::class, $this->constructEmpty(LoggerInterface::class));
        $this->app = $this->make(new App($this->container), [
            'init' => new VkDto(1, $this->data, 'test')
        ]);
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
            ['run' => fn() => true]
        );
        $this->container->set('vk-commands', [$command]);
        $this->data->text = $message;
        $this->app = $this->make(new App($this->container), [
            'init' => new VkDto(1, $this->data, $message)
        ]);
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
        $this->data->text = 'test';
        $this->app = $this->make(new App($this->container), [
            'init' => new VkDto(1, $this->data, 'test')
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
            ['test', ['test'], []],
            ['some text', ['test'], []],
            ['test', ['test <args>'], []],
            ['test value1', ['test <args>'], ['value1']],
            ['command value1 value2 value3', ['command <args>'], ['value1', 'value2', 'value3']],
            ['some text', ['command <args>'], []],
        ];
    }
}

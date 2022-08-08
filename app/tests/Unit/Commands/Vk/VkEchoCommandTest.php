<?php
namespace Tests\Unit\Commands\Vk;

use Arslav\Newbot\Commands\Vk\EchoCommand;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Arslav\Newbot\App;
use DigitalStar\vk_api\vk_api;
use DigitalStar\vk_api\VkApiException;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\Support\UnitTester;

class VkEchoCommandTest extends Unit
{
    protected UnitTester $tester;

    /**
     * @return void
     *
     * @throws Exception
     */
    protected function setUp(): void
    {
        $container = App::getContainer();
        $container->set(vk_api::class, $this->constructEmpty(
            vk_api::class,
            [null, null],
            ['reply' => Expected::once()]
        ));
        parent::setUp();
    }

    /**
     * @return void
     *
     * @throws VkApiException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testRun()
    {
        $this->tester->sendMessage('test');
        $command = new EchoCommand(['test']);
        $command->init($this->tester->getVkMessageData());
        $command->run();
    }
}

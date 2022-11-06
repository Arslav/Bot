<?php

namespace Tests\Unit;

use Throwable;
use Exception;
use Arslav\Bot\App;
use Codeception\Test\Unit;
use Codeception\Stub\Expected;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

class AppTest extends Unit
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetEntityManager()
    {
        App::getContainer()->set(EntityManager::class, $this->makeEmpty(EntityManager::class));
        $this->assertInstanceOf(EntityManager::class, App::getEntityManager());
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
     * @return void
     */
    public function testGetInstance(): void
    {
        $this->assertInstanceOf(App::class, App::getInstance());
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Throwable
     */
    public function testRun(): void
    {
        $app = $this->construct(App::class, [App::getContainer()], [
            'execute' => Expected::once(),
            'getName' => 'Stub',
        ]);
        $app->run();
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Throwable
     */
    public function testRunException(): void
    {
        $app = $this->construct(App::class, [App::getContainer()], [
            'execute' => Expected::once(function () {
                throw new Exception();
            }),
            'getName' => 'Stub',
        ]);
        $this->expectException(Exception::class);
        $app->run();
    }
}

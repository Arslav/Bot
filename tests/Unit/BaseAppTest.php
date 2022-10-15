<?php

namespace Tests\Unit;

use Arslav\Bot\BaseApp;
use Arslav\Bot\Vk\App;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

class BaseAppTest extends Unit
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function testGetEntityManager()
    {
        BaseApp::getContainer()->set(EntityManager::class, $this->makeEmpty(EntityManager::class));
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
}
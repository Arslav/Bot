<?php

namespace Tests\Unit\Commands\Vk\Base;

use Arslav\Newbot\App;
use Arslav\Newbot\Commands\Vk\Base\LimitedVkCommand;
use Arslav\Newbot\Entities\CommandLog;
use Arslav\Newbot\Services\CommandStats;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\Support\UnitTester;

class LimitedVkCommandTest extends Unit
{
    protected UnitTester $tester;

    protected CommandStats $service;

    protected LimitedVkCommand $command;

    /**
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    protected function setUp(): void
    {
        $container = ContainerBuilder::build();
        $this->command = $this->construct(
            LimitedVkCommand::class,
            [['test']],
            ['run' => Expected::never()]
        );
        $this->command->service = $container->get(CommandStats::class);
        parent::setUp();
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws DependencyException
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testBeforeAction()
    {
        $this->tester->sendMessage('test');
        $this->command->init($this->tester->getVkMessageData());
        $this->assertSame(true, $this->command->beforeAction());
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testBeforeActionLimit()
    {
        $this->tester->sendMessage('test');
        $this->command->init($this->tester->getVkMessageData());

        for($i = 0; $i < $this->command->limit; $i++) {
            $this->command->beforeAction();
        }

        $this->assertSame(false, $this->command->beforeAction());
        $this->tester->seeInRepository(CommandLog::class, [
            'command' => $this->command::class,
            'from_id' => $this->command->from_id,
        ]);
    }
}

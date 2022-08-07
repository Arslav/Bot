<?php

namespace Tests\Unit\Services;

use Arslav\Newbot\App;
use Arslav\Newbot\Entities\CommandLog;
use Arslav\Newbot\Services\CommandStats;
use Codeception\Test\Unit;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\Support\Fixture\FileFixture;
use Tests\Support\UnitTester;

class CommandStatsTest extends Unit
{
    protected CommandStats $service;

    protected UnitTester $tester;

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        $this->service = new CommandStats();
        $loader = new Loader();
        $loader->addFixture(new FileFixture(CommandLog::class, 'command_log.php'));
        $executor = new ORMExecutor(App::getEntityManager(), new ORMPurger());
        $executor->execute($loader->getFixtures());
        parent::setUp();
    }

    /**
     * @param string $command
     * @param int $from_id
     * @param int|null $chat_id
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     *
     * @dataProvider commandProvider
     */
    public function testSaveUsage(string $command, int $from_id, ?int $chat_id)
    {
        $this->service->saveUsage($command, $from_id, $chat_id);

        $params = [
            'command' => $command,
            'from_id' => $from_id,
        ];

        if ($chat_id) {
            $params['chat_id'] = $chat_id;
        } else {
            $params[] = Criteria::expr()->isNull('chat_id');
        }

        $this->tester->seeInRepository(CommandLog::class, $params);
    }

    /**
     * @return void
     *
     * @dataProvider commandUsagesProvider
     */
    public function testUsagesByInterval(string $command, int $from_id, ?int $chat_id, int $expected)
    {
        $actual = $this->service->usagesByInterval(300, $command, $from_id, $chat_id);
        $this->assertSame($expected, $actual);
    }

    /**
     * @return array
     */
    public function commandProvider(): array
    {
        return [
            ['test', 1, null],
            ['test', 1, 1]
        ];
    }

    /**
     * @return array
     */
    public function commandUsagesProvider(): array
    {
        return [
            ['test1', 1, 1, 5],
            ['test1', 1, 2, 1],
            ['test1', 1, null, 2],
            ['test2', 1, 1, 0],
            ['test2', 1, null, 0],
        ];
    }
}

<?php

namespace Tests\Unit;

use Exception;
use Codeception\Test\Unit;
use Arslav\Bot\BaseCommand;
use Codeception\Stub\Expected;

class BaseCommandTest extends Unit
{
    private BaseCommand $command;

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->command = $this->construct(
            BaseCommand::class, [['test']],
            ['execute' => true],
        );
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testSetArgs(): void
    {
        $value = ['test1', 'test2'];
        $this->command->setArgs($value);
        $this->assertSame($value, $this->command->getArgs());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testConstructSingleAlias(): void
    {
        $this->command = $this->construct(
            BaseCommand::class, ['test'],
            ['execute' => true],
        );
        $this->assertSame(['test'], $this->command->getAliases());
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testRun(): void
    {
        $this->command = $this->construct(
            BaseCommand::class, ['test'],
            ['execute' => Expected::once()],
        );
        $this->command->run();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testBeforeAction(): void
    {
        $this->command = $this->construct(
            BaseCommand::class, ['test'],
            [
                'beforeAction' => false,
                'execute' => Expected::never()
            ],
        );
        $this->command->run();
    }
}

<?php

namespace Tests\Unit;

use Exception;
use Codeception\Test\Unit;
use Arslav\Bot\Command;
use Codeception\Stub\Expected;

class CommandTest extends Unit
{
    private Command $command;

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->command = $this->construct(
            Command::class, [['test']],
            ['execute' => true],
        );
        parent::setUp();
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testConstructSingleAlias(): void
    {
        $this->command = $this->construct(
            Command::class, ['test'],
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
            Command::class, ['test'],
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
            Command::class, ['test'],
            [
                'beforeAction' => false,
                'execute' => Expected::never()
            ],
        );
        $this->command->run();
    }
}

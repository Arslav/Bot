<?php

namespace Tests\Unit;

use Exception;
use Codeception\Test\Unit;
use Arslav\Bot\BaseCommand;
use InvalidArgumentException;

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
            ['run' => true],
        );
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testBeforeAction(): void
    {
        $this->assertSame(true, $this->command->beforeAction());
    }

    /**
     * @return void
     */
    public function testSetArgs(): void
    {
        $value = ['test1', 'test2'];
        $this->command->setArgs($value);
        $this->assertSame($value, $this->command->args);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testConstructSingleAlias(): void
    {
        $this->command = $this->construct(
            BaseCommand::class, ['test'],
            ['run' => true],
        );
        $this->assertSame(['test'], $this->command->aliases);
    }
}

<?php

namespace Tests\Unit;

use Arslav\Bot\BaseCommand;
use Codeception\Test\Unit;
use Exception;

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
}

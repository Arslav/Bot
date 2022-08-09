<?php

namespace Tests\Unit\Commands;

use Arslav\Bot\Commands\Command;
use Codeception\Test\Unit;
use Exception;

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
            ['run' => true],
        );
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testBeforeAction()
    {
        $this->assertSame(true, $this->command->beforeAction());
    }

    /**
     * @return void
     */
    public function testSetArgs()
    {
        $value = ['test1', 'test2'];
        $this->command->setArgs($value);
        $this->assertSame($value, $this->command->args);
    }
}

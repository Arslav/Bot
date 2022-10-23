<?php

namespace Tests\Unit\Cli;

use Arslav\Bot\Cli\Command;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Exception;

class CommandTest extends Unit
{
    /**
     * @return void
     *
     * @throws Exception
     */
    public function testRun(): void
    {
        $command = $this->construct(
            Command::class,
            [['test']],
            ['run' => Expected::once()]
        );
        $command->run();
    }

    /**
     * @return void
     */
    public function testGetDescription(): void
    {
        /**
         * Test
         */
        $command = new class(['test']) extends Command {
            function run(): void
            {
                //Stub
            }
        };

        $this->assertSame('Test', $command->getDescription());
    }

    /**
     * @return void
     */
    public function testGetNullDescription(): void
    {
        $command = new class(['test']) extends Command {
            function run(): void
            {
                //Stub
            }
        };

        $this->assertNull($command->getDescription());
    }
}

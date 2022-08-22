<?php

namespace Tests\Unit\Commands\Cli\Base;

use Arslav\Bot\Commands\Cli\Base\CliCommand;
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
            CliCommand::class,
            [['test']],
            ['run' => Expected::once()]
        );
        $command->run();
    }

    /**
     * @return void
     */
    public function testGetDescription()
    {
        /**
         * Test
         */
        $command = new class(['test']) extends CliCommand {
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
    public function testGetNullDescription()
    {
        $command = new class(['test']) extends CliCommand {
            function run(): void
            {
                //Stub
            }
        };

        $this->assertNull($command->getDescription());
    }
}

<?php

namespace Test\Unit\Commands\Cli\Base;

use Arslav\Newbot\Commands\Cli\Base\CliCommand;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Exception;

class CliCommandTest extends Unit
{
    /**
     * @return void
     *
     * @throws Exception
     */
    public function testRun()
    {
        $command = $this->construct(
            CliCommand::class,
            [['test']],
            ['run' => Expected::once()]
        );
        $command->run();
    }
}

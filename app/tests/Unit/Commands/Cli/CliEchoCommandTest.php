<?php

namespace Tests\Unit\Commands\Cli;

use Arslav\Newbot\Commands\Cli\EchoCommand;
use Codeception\Test\Unit;
use ReflectionClass;
use Tests\Support\UnitTester;

class CliEchoCommandTest extends Unit
{
    protected UnitTester $tester;

    /**
     * @return void
     */
    public function testRun()
    {
        $command = new EchoCommand(['echo']);
        $command->setArgs(['test']);
        $command->run();
    }
}

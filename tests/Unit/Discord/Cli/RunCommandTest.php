<?php

namespace Tests\Unit\Discord\Cli;

use Exception;
use ReflectionClass;
use Codeception\Stub;
use ReflectionException;
use Codeception\Test\Unit;
use Arslav\Bot\Discord\App;
use Arslav\Bot\Discord\Command;
use Arslav\Bot\Discord\Cli\RunCommand;

/**
 * Class RunCommandTest
 *
 * @package Tests\Unit\Discord\Cli
 */
class RunCommandTest extends Unit
{
    /**
     * @return void
     * @throws Exception
     */
    public function testExecute(): void
    {
        $command = $this->make(RunCommand::class, [
            'createApp' => fn () => $this->makeEmpty(App::class, [
                'run' => Stub\Expected::once()
            ])
        ]);

        $command->run();
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testCreateApp(): void
    {
        $command = $this->make(RunCommand::class);
        $reflectionClass = new ReflectionClass($command);
        $method = $reflectionClass->getMethod('createApp');
        $app = $method->invoke($command);
        $this->assertInstanceOf(App::class, $app);
    }
}


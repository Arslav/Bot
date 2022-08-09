<?php


namespace Tests\Unit\Commands\Cli;

use Arslav\Bot\App;
use Arslav\Bot\Commands\Cli\Base\CliCommand;
use Arslav\Bot\Commands\Cli\HelpCommand;
use Codeception\Test\Unit;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class HelpCommandTest extends Unit
{
    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function testRun()
    {
        $container = App::getContainer();
        $container->set('cli-commands', [
            $this->constructEmpty(CliCommand::class, [['test']], ['run' => true]),
            /**
             * Test
             */
            new class(['command']) extends CliCommand {
                function run(): void
                {
                    //Stub
                }
            }
        ]);

        $command = new HelpCommand();
        $command->run();
    }
}

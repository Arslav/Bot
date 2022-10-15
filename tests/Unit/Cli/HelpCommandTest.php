<?php


namespace Tests\Unit\Cli;

use Arslav\Bot\Cli\Command;
use Arslav\Bot\Cli\HelpCommand;
use Arslav\Bot\Vk\App;
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
    public function testRun(): void
    {
        $container = App::getContainer();
        $container->set('cli-commands', [
            $this->constructEmpty(Command::class, [['test']], ['run' => true]),
            /**
             * Test
             */
            new class(['command']) extends Command {
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

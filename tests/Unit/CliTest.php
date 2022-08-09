<?php

namespace Tests\Unit;

use Arslav\Bot\Cli;
use Arslav\Bot\Commands\Cli\Base\CliCommand;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Arslav\Bot\App;
use DI\Container;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

class CliTest extends Unit
{

    private ?Container $container;

    protected function setUp(): void
    {
        $this->container = App::getContainer();
        $this->container->set(LoggerInterface::class, $this->constructEmpty(LoggerInterface::class));
        parent::setUp();
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     *
     * @dataProvider commandProvider
     */
    public function testRun(array $aliases, array $args, bool $expectRun)
    {
        $command = $this->construct(
            CliCommand::class,
            [$aliases],
            ['run' => Expected::exactly((int) $expectRun)]
        );
        $this->container->set('cli-commands', [$command]);
        $app = new Cli($this->container, array_merge(['./bin/console', 'test'], $args));
        $app->run();
        if ($expectRun) {
            $this->assertSame($args, $command->args);
        }
    }

    /**
     * @return array[]
     */
    public function commandProvider(): array
    {
        return [
            [['test'], [], true],
            [['test'], ['arg1', 'arg2'], true],
            [['command'], ['arg1', 'arg2'], false],
            [['command'], [], false]
        ];
    }
}

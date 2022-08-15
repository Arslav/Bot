<?php

namespace Tests\Unit;

use Arslav\Bot\Cli;
use Arslav\Bot\Commands\Cli\Base\CliCommand;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Arslav\Bot\App;
use DI\Container;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CliTest extends Unit
{

    private ?Container $container;

    protected function setUp(): void
    {
        $this->container = App::getContainer();
        parent::setUp();
    }

    /**
     * @param array $aliases
     * @param array $args
     * @param bool $expectRun
     *
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     *
     * @dataProvider commandProvider
     */
    public function testRun(array $aliases, array $args, bool $expectRun): void
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

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function testRunWithError(): void
    {
        $app = new Cli($this->container, array_merge(['./bin/console', 'test']));
        Cli::getContainer()->set('cli-commands', [
            $this->construct(CliCommand::class, [['test']], [
                'run' => function() {
                    throw new Exception('test exception');
                }
            ]),
        ]);
        Stub::update(Cli::getLogger(), [
            'error' => Expected::once(),
        ]);
        try {
            $app->run();
        } catch (Exception $e) {
            $this->assertSame('test exception', $e->getMessage());
        }
    }
}

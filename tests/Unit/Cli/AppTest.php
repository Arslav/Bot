<?php

namespace Tests\Unit\Cli;

use Throwable;
use Arslav\Bot\BaseApp;
use Arslav\Bot\Cli\App;
use Arslav\Bot\Cli\Command;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use DI\Container;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AppTest extends Unit
{
    private ?Container $container;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->container = BaseApp::getContainer();
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
     * @throws Throwable
     *
     * @dataProvider commandProvider
     */
    public function testRun(array $aliases, array $args, bool $expectRun): void
    {
        $command = $this->construct(
            Command::class,
            [$aliases],
            ['execute' => Expected::exactly((int) $expectRun)]
        );
        $this->container->set('cli-commands', [$command]);
        $app = new App($this->container, array_merge(['./bin/console', 'test'], $args));
        $app->run();
        if ($expectRun) {
            $this->assertSame($args, $command->getArgs());
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
     * @throws Throwable
     */
    public function testRunWithError(): void
    {
        $app = new App($this->container, array_merge(['./bin/console', 'test']));
        App::getContainer()->set('cli-commands', [
            $this->construct(Command::class, [['test']], [
                'execute' => function() {
                    throw new Exception('test exception');
                }
            ]),
        ]);
        Stub::update(App::getLogger(), [
            'error' => Expected::once(),
        ]);
        try {
            $app->run();
        } catch (Exception $e) {
            $this->assertSame('test exception', $e->getMessage());
        }
    }
}

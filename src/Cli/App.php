<?php

namespace Arslav\Bot\Cli;

use Arslav\Bot\BaseApp;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class App extends BaseApp
{
    private ?array $args;
    private string $commandAlias;

    /**
     * @param ContainerInterface $container
     * @param array              $args
     */
    public function __construct(ContainerInterface $container, array $args)
    {
        array_shift($args);
        $this->commandAlias = $args[0] ?? 'help';
        array_shift($args);
        $this->args = $args;
        parent::__construct($container);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'CLI';
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function onStart(): void
    {
        /** @var Command $command */
        $commands = self::getContainer()->get('cli-commands');
        $helpCommand = new HelpCommand();
        $commands[] = $helpCommand;

        foreach ($commands as $command) {
            if (in_array($this->commandAlias, $command->aliases)) {
                $this->runCommand($command, null, $this->args ?? []);
            }
        }
        echo "Ошибка! Команда $this->commandAlias не распознана!" . PHP_EOL . PHP_EOL;
        $helpCommand->run();
    }
}

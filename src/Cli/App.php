<?php

namespace Arslav\Bot\Cli;

use Exception;
use Arslav\Bot\App as BaseApp;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

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
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    protected function execute(): void
    {
        /** @var Command $command */
        $commands = self::getContainer()->get('cli-commands');
        $helpCommand = new HelpCommand();
        $commands[] = $helpCommand;

        foreach ($commands as $command) {
            if (in_array($this->commandAlias, $command->getAliases())) {
                $command->run(null, $this->args ?? []);
                return;
            }
        }
        echo "Ошибка! Команда $this->commandAlias не распознана!" . PHP_EOL . PHP_EOL;
        $helpCommand->run();
    }
}

<?php

namespace Arslav\Newbot;

use Arslav\Newbot\Commands\Cli\Base\CliCommand;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Cli extends App
{
    private ?array $args;

    private string $commandAlias;

    /**
     * @param ContainerInterface $container
     * @param array $args
     */
    public function __construct(ContainerInterface $container, array $args)
    {
        //TODO: В случае запуска без аргументов выводить справку
        array_shift($args);
        $this->commandAlias = $args[0];

        array_shift($args);
        $this->args = $args;

        parent::__construct($container);
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function run(): void
    {
        self::getLogger()->info('Launched from CLI');
        self::getLogger()->debug('Args: ' . print_r($this->args, true));

        /** @var CliCommand $command */
        $commands = self::$container->get('cli-commands');
        foreach ($commands as $command) {
            if (in_array($this->commandAlias, $command->aliases)) {
                self::getLogger()->info('Command detected: ' . get_class($command));
                if ($command->beforeAction()) {
                    if ($this->args) {
                        $command->setArgs($this->args);
                    }
                    $command->run();
                    return;
                }
            }
        }
    }
}
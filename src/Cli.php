<?php

namespace Arslav\Bot;

use Arslav\Bot\Commands\Cli\Base\CliCommand;
use Arslav\Bot\Commands\Cli\HelpCommand;
use Exception;
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
    public function run(): void
    {
        self::getLogger()->info('App started');
        self::getLogger()->info('Launched from CLI');
        self::getLogger()->debug('Args: ' . print_r($this->args, true));

        try {
            /** @var CliCommand $command */
            $commands = self::getContainer()->get('cli-commands');
            $helpCommand = new HelpCommand();
            $commands[] = $helpCommand;

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
            echo "Ошибка! Команда $this->commandAlias не распознана!" . PHP_EOL . PHP_EOL;

            $helpCommand->run();
        } catch (Exception $e) {
            App::getLogger()->error($e->getMessage(), $e->getTrace());
            throw $e;
        } finally {
            App::getLogger()->info('App end');
        }
    }
}

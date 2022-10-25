<?php

namespace Arslav\Bot\Cli;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Вывод справки
 *
 * @package Arslav\Bot\Cli
 */
class HelpCommand extends Command
{
    public function __construct()
    {
        parent::__construct(['help', '-h', '--help']);
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function execute(): void
    {
        echo 'Доступные команды: ' . PHP_EOL;
        $commands = App::getContainer()->get('cli-commands');
        $commands[] = $this;
        /** @var Command $command */
        foreach ($commands as $command) {
            echo ' - ' . $command->aliases[0];
            if ($description = $command->getDescription()) {
                echo ' - ' . $description;
            }
            echo PHP_EOL;
        }
    }
}

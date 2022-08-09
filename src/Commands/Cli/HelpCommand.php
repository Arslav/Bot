<?php

namespace Arslav\Bot\Commands\Cli;

use Arslav\Bot\Cli;
use Arslav\Bot\Commands\Cli\Base\CliCommand;
use JetBrains\PhpStorm\Pure;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class HelpCommand extends CliCommand
{

    #[Pure] public function __construct()
    {
        parent::__construct(['help', '-h', '--help']);
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function run(): void
    {
        echo 'Доступные команды: ' . PHP_EOL;
        $commands = Cli::getContainer()->get('cli-commands');
        /** @var CliCommand $command */
        foreach ($commands as $command)
        {
            echo ' - ' . $command->aliases[0];
            if ($description = $command->getDescription())
            {
                echo ' - ' . $description;
            }
            echo PHP_EOL;
        }
    }
}
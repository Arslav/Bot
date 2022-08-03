<?php

namespace Arslav\Newbot;

use Arslav\Newbot\Commands\Cli\Base\CliCommand;
use Psr\Container\ContainerInterface;

class Cli extends App
{

    public function __construct(ContainerInterface $container, array $args) {
        self::$args = $args;
        parent::__construct($container);
    }

    public function run() : void
    {
        self::getLogger()->info('Launched from CLI');
        $commands = self::$container->get('cli-commands');
        self::getLogger()->debug('Args: ' . print_r(self::$args, true));
        if(isset(self::$args[1])) {
            /** @var CliCommand $command */
            foreach ($commands as $command) {
                if (in_array(self::$args[1], $command->aliases)) {
                    self::getLogger()->info('Command detected: ' . get_class($command));
                    if ($command->beforeAction()) {
                        $status = $command->run();
                        exit($status);
                    }
                }
            }
        }
    }

}
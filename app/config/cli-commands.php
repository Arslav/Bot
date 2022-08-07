<?php

use Arslav\Newbot\Commands\Cli\EchoCommand;
use function DI\create;
use function DI\get;

return [
    EchoCommand::class => create()->constructor(['echo']),

    'cli-commands' => [
        get(EchoCommand::class),
    ],
];

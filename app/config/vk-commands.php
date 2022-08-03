<?php

use Arslav\Newbot\Commands\Vk\EchoCommand;
use function DI\create;
use function DI\get;

return [
    EchoCommand::class => create()
        ->constructor([
            '^ыы+$',
            '^кря+$',
            '^ря+$',
            '^[аы]{2,}$'
        ]),

    'vk-commands' => [
        get(EchoCommand::class),
    ]
];
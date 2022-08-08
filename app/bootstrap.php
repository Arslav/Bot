<?php

require_once 'vendor/autoload.php';
require_once 'helpers.php';

$builder = new DI\ContainerBuilder();
$builder->useAnnotations(true);
$builder->addDefinitions(__DIR__.'/config/container.php');
$builder->addDefinitions(__DIR__.'/config/services.php');
$builder->addDefinitions(__DIR__.'/config/cli-commands.php');
$builder->addDefinitions(__DIR__.'/config/vk-commands.php');

return $builder->build();

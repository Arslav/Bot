<?php

use DI\Container;

require_once 'vendor/autoload.php';
require_once 'helpers.php';

class ContainerBuilder {

    public static ?Container $container = null;

    /**
     * @return Container|null
     */
    public static function build(): ?Container
    {
        if (!static::$container) {
            $builder = new DI\ContainerBuilder();
            $builder->useAnnotations(true);
            $builder->addDefinitions(__DIR__.'/config/container.php');
            $builder->addDefinitions(__DIR__.'/config/cli-commands.php');
            $builder->addDefinitions(__DIR__.'/config/vk-commands.php');
            static::$container = $builder->build();
        }
        return static::$container;
    }
}

return ContainerBuilder::build();

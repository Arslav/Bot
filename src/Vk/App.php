<?php

namespace Arslav\Bot\Vk;

use Arslav\Bot\BaseApp;
use DI\NotFoundException;
use DI\DependencyException;
use DigitalStar\vk_api\vk_api;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class App extends BaseApp
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'VK';
    }

    /**
     * @return Bot
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws DependencyException
     * @throws NotFoundException
     */
    public static function bot(): Bot
    {
        return self::getContainer()->get(Bot::class);
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
        $message = self::bot()->getMessage();
        if ($message == null) {
            self::getLogger()->info('No data received');
            return;
        }

        /** @var Command $command */
        $commands = self::getContainer()->get('vk-commands');
        foreach ($commands as $command) {
            foreach ($command->getAliases() as $alias) {
                $args = [];
                if ($this->checkAlias($alias, $message->getContent(), $args)) {
                    $command->run($message, $args);
                    return;
                }
            }
        }
    }
}

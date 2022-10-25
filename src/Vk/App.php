<?php

namespace Arslav\Bot\Vk;

use Arslav\Bot\BaseApp;
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
     * @return vk_api
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public static function getVk(): vk_api
    {
        return self::getContainer()->get(vk_api::class);
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
        $data = $this->init();
        if ($data == null) {
            self::getLogger()->info('No data received');
            return;
        }
        //TODO: Разделить команды по типу триггеров! message_new и т.д.!!!
        if ($data->type != 'message_new') {
            self::getLogger()->error('Unsupported type');
            return;
        }

        self::getLogger()->info('New message: '. print_r($data->object->text, true));

        /** @var Command $command */
        $commands = self::getContainer()->get('vk-commands');
        foreach ($commands as $command) {
            foreach ($command->getAliases() as $alias) {
                $args = [];
                if ($this->checkAlias($alias, $data->object->text, $args)) {
                    $command->run($data, $args);
                    return;
                }
            }
        }
    }

    /**
     * @return object|null
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function init(): ?object
    {
        $data = self::getVk()->initVars();
        self::getLogger()->debug('Received data: ' . print_r($data, true));

        if (!$data) {
            return null;
        }
        
        return (object) $data;
    }
}

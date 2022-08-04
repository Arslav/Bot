<?php

namespace Arslav\Newbot;

use Arslav\Newbot\Commands\Vk\Base\VkCommand;
use Arslav\Newbot\DTO\VkDto;
use DigitalStar\vk_api\vk_api;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

class App
{
    protected static ContainerInterface $container;

    protected static LoggerInterface $logger;

    protected static EntityManager $entityManager;

    public function __construct(ContainerInterface $container)
    {
        self::$container = $container;
    }

    /**
     * @return vk_api
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getVk(): vk_api
    {
        return self::$container->get(vk_api::class);
    }

    /**
     * @return LoggerInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getLogger(): LoggerInterface
    {
        return self::$container->get(LoggerInterface::class);
    }

    /**
     * @return EntityManager
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function getEntityManager() : EntityManager
    {
        return self::$container->get(EntityManager::class);
    }

    /**
     * @return ContainerInterface
     */
    public static function getContainer(): ContainerInterface
    {
        return self::$container;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function run() : void
    {
        $vkDto = $this->init();
        if($vkDto == null) {
            self::getLogger()->info('No data received');
            return;
        }
        //TODO: Разделить команды по типу триггеров! message_new и т.д.!!!
        if ($vkDto->data->type != 'message_new') {
            self::getLogger()->error('Unsupported type');
            return;
        }

        self::getLogger()->info('New message: '. print_r($vkDto->message, true));
        /** @var VkCommand $command */
        $commands = self::$container->get('vk-commands');
        foreach ($commands as $command) {
            foreach ($command->aliases as $alias) {
                $regex = $this->getRegex($alias);
                if (preg_match($regex, $vkDto->message, $matches)) {
                    if (isset($matches['args'])) {
                        $args = $this->prepareArgs($matches['args']);
                        $command->setArgs($args);
                    }
                    $this->runCommand($command, $vkDto->data);
                    return;
                }
            }
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function runCommand(VkCommand $command, mixed $data)
    {
        self::getLogger()->info('Command detected: ' . get_class($command));
        $command->init($data);
        if($command->beforeAction()) {
            $command->run();
        }
    }

    /**
     * @param string $args
     *
     * @return array
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function prepareArgs(string $args): array
    {
        $result = explode(' ', $args);
        self::getLogger()->debug('Parsed Args: ' . print_r($result, true));
        return $result;
    }

    /**
     * @param $alias
     * @return string
     */
    protected function getRegex($alias): string
    {
        //TODO: Подумать на тему префиксов
        $regex = str_replace('<args>', '(?<args>.*)', $alias);
        return "/$regex/ui";
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    protected function init(): ?VkDto
    {
        self::getLogger()->info('App started');
        self::getLogger()->info('Launched from VK');
        $data = self::getVk()->initVars($id, $message);
        self::getLogger()->debug('Received data: ' . print_r($data, true));
        if ($data) {
            return new VkDto($id, $data, $message);
        }
        return null;
    }
}
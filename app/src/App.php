<?php

namespace Arslav\Newbot;

use Arslav\Newbot\Commands\Vk\Base\VkCommand;
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

    protected static array $args;

    public function __construct(ContainerInterface $container) {
        self::$container = $container;
    }

    /**
     * @return array
     */
    public static function getArgs(): array
    {
        return self::$args;
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
        self::getLogger()->info('App started');
        self::getLogger()->info('Launched from VK');
        $commands = self::$container->get('vk-commands');
        $data = self::getVk()->initVars($id, $message);
        self::getLogger()->debug('Received data: ' . print_r($data, true));
        if($data != null) {
            //TODO: Разделить команды по типу триггеров! message_new и т.д.!!!
            if ($data->type == 'message_new') {
                self::getLogger()->info('New message: '. print_r($message, true));
                $message = $data->object->text;
                /** @var VkCommand $command */
                foreach ($commands as $command) {
                    foreach ($command->aliases as $alias) {
                        //TODO: Подумать на тему префиксов
                        $regex = str_replace('<args>', '(?<args>.*)', $alias);
                        $regex = "/$regex/ui";
                        if (preg_match($regex, $message, $matches)) {
                            self::getLogger()->debug($regex);
                            self::getLogger()->info('Command detected: ' . get_class($command));
                            $str_args = $matches['args'] ?? '';
                            self::$args = explode(' ', $str_args);
                            self::getLogger()->debug('Parsed Args: ' . print_r(self::$args, true));
                            $command->init($data);
                            if($command->beforeAction()) {
                                $status = $command->run();
                                self::getLogger()->info("Command executed with status: $status");
                                return;
                            }
                        }
                    }
                }
            }
        }
        self::getLogger()->info('App ended');
    }
}
<?php

namespace Arslav\Bot;

use Arslav\Bot\Commands\Vk\Base\VkCommand;
use Arslav\Bot\DTO\VkDto;
use DI\Container;
use DigitalStar\vk_api\vk_api;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

class App
{
    protected static ContainerInterface $container;
    protected static App $instance;

    /**
     * @return App
     */
    public static function getInstance(): App
    {
        return self::$instance;
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
     * @return LoggerInterface
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public static function getLogger(): LoggerInterface
    {
        return self::getContainer()->get(LoggerInterface::class);
    }

    /**
     * @return EntityManager
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public static function getEntityManager() : EntityManager
    {
        return self::getContainer()->get(EntityManager::class);
    }

    /**
     * @return ContainerInterface|Container
     */
    public static function getContainer(): ContainerInterface|Container
    {
        return self::$container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        self::$container = $container;
        self::$instance = $this;
    }

    /**
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function run() : void
    {
        try {
            $data = $this->init();
            if($data == null) {
                self::getLogger()->info('No data received');
                return;
            }
            //TODO: Разделить команды по типу триггеров! message_new и т.д.!!!
            if ($data->type != 'message_new') {
                self::getLogger()->error('Unsupported type');
                return;
            }

            self::getLogger()->info('New message: '. print_r($data->object->text, true));

            /** @var VkCommand $command */
            $commands = self::getContainer()->get('vk-commands');
            foreach ($commands as $command) {
                foreach ($command->aliases as $alias) {
                    $regex = $this->getRegex($alias);
                    if (preg_match($regex, $data->object->text, $matches)) {
                        if (isset($matches['args'])) {
                            $args = $this->prepareArgs($matches['args']);
                            $command->setArgs($args);
                        }
                        $this->runCommand($command, $data);
                        return;
                    }
                }
            }
        } catch (Exception $e) {
            App::getLogger()->error($e->getMessage(), $e->getTrace());
            throw $e;
        } finally {
            App::getLogger()->info('App end');
        }
    }

    /**
     * @param VkCommand $command
     * @param mixed $data
     *
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function runCommand(VkCommand $command, mixed $data): void
    {
        self::getLogger()->info('Command detected: ' . get_class($command));
        $command->init($data);
        if ($command->beforeAction()) {
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
     * @param string $alias
     *
     * @return string
     */
    protected function getRegex(string $alias): string
    {
        //TODO: Подумать на тему префиксов
        //TODO: Подумать на тему ограничения колва аргументов <args:3> <args:*>...
        $regex = str_replace('<args>', '(?<args>.*)', $alias);

        return "/$regex/ui";
    }

    /**
     * @return VkDto|null
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function init(): ?object
    {
        self::getLogger()->info('App started');
        self::getLogger()->info('Launched from VK');
        $data = self::getVk()->initVars();
        self::getLogger()->debug('Received data: ' . print_r($data, true));

        if ($data) {
            return $data;
        }

        return null;
    }
}

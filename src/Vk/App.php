<?php

namespace Arslav\Bot\Vk;

use Arslav\Bot\BaseApp;
use DI\Container;
use DigitalStar\vk_api\vk_api;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;

class App extends BaseApp
{
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
    public function run() : void
    {
        try {
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
     * @param Command $command
     * @param mixed $data
     *
     * @return void
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function runCommand(Command $command, mixed $data): void
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
     * @return object|null
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

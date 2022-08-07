<?php

namespace Tests\Support\Modules;

use Arslav\Newbot\App;
use Codeception\Exception\ModuleException;
use Codeception\Lib\Interfaces\DoctrineProvider;
use Codeception\Lib\ModuleContainer;
use Codeception\Module;
use Codeception\Module\Cli;
use ContainerBuilder;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\PostgreSQLSchemaManager;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DatabaseMigration extends Module implements DoctrineProvider
{
    protected static bool $migrated = false;

    protected ?Container $container;

    /**
     * @param ModuleContainer $moduleContainer
     * @param array|null $config
     * @throws \Exception
     */
    public function __construct(ModuleContainer $moduleContainer, ?array $config = null)
    {
        $this->container = ContainerBuilder::build();
        parent::__construct($moduleContainer, $config);
    }

    /**
     * @throws NotFoundException
     * @throws DependencyException
     * @throws Exception
     * @throws ModuleException
     */
    protected function migrate()
    {
        if (!self::$migrated) {
            $database = $this->container->get('DB_NAME');
            $this->createDatabase($database);

            /** @var Cli $cli */
            $cli = $this->getModule(Cli::class);
            $cli->runShellCommand("DB_NAME={$database} bin/doctrine migrations:migrate -n");
            self::$migrated = true;
        }
    }

    /**
     * @return EntityManager
     * @throws DependencyException
     * @throws ModuleException
     * @throws NotFoundException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function _getEntityManager(): EntityManager
    {
        $this->migrate();
        return App::getEntityManager();
    }

    /**
     * @return void
     *
     * @throws DependencyException
     * @throws Exception
     * @throws NotFoundException
     */
    protected function createDatabase(string $database)
    {
        $params = $this->container->get('DbConnectionParams');
        unset($params['dbname']);

        $manager = DriverManager::getConnection($params)->createSchemaManager();
        if (!in_array($database, $manager->listDatabases())) {
            $manager->createDatabase($database);
        }
    }
}

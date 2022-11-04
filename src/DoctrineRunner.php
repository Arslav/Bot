<?php

namespace Arslav\Bot;

use Doctrine\DBAL\Connection;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ExistingConfiguration;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Metadata\Storage\TableMetadataStorageConfiguration;
use Doctrine\Migrations\Provider\OrmSchemaProvider;
use Doctrine\Migrations\Provider\SchemaProvider;
use Doctrine\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\Migrations\Tools\Console\Command\DumpSchemaCommand;
use Doctrine\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\Migrations\Tools\Console\Command\LatestCommand;
use Doctrine\Migrations\Tools\Console\Command\ListCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\Migrations\Tools\Console\Command\RollupCommand;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\Migrations\Tools\Console\Command\SyncMetadataCommand;
use Doctrine\Migrations\Tools\Console\Command\VersionCommand;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Class DoctrineRunner
 *
 * @package Arslav\Bot
 */
class DoctrineRunner
{
    protected const MIGRATION_PATH = 'src/Migration';
    protected const MIGRATION_TABLE = 'doctrine_migration_versions';

    protected ContainerInterface $container;
    protected EntityManager $entityManager;
    protected Connection $connection;
    protected Configuration $configuration;
    protected DependencyFactory $dependencyFactory;
    protected array $commandList;

    /**
     * @param ContainerInterface $container
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->entityManager = $this->container->get(EntityManager::class);
        $this->connection = $this->container->get(Connection::class);
        $this->configuration = $this->getConfiguration();
        $this->dependencyFactory = $this->getDependencyFactory();
        $this->commandList = $this->getDoctrineCommandsList();
    }

    /**
     * @codeCoverageIgnore
     *
     * @return void
     */
    public function run(): void
    {
        ConsoleRunner::run(
            new SingleManagerProvider($this->entityManager),
            $this->commandList
        );
    }

    /**
     * @return TableMetadataStorageConfiguration
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function configureStorage(): TableMetadataStorageConfiguration
    {
        $storageConfiguration = new TableMetadataStorageConfiguration();
        $storageConfiguration->setTableName(
            $this->container->get('migration')['table_name'] ?? self::MIGRATION_TABLE
        );

        return $storageConfiguration;
    }

    /**
     * @return DependencyFactory
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     *
     * @psalm-suppress InternalMethod
     * @psalm-suppress InternalClass
     */
    protected function getDependencyFactory(): DependencyFactory
    {
        $df = DependencyFactory::fromConnection(
            new ExistingConfiguration($this->getConfiguration()),
            new ExistingConnection($this->connection)
        );
        $df->setService(SchemaProvider::class, new OrmSchemaProvider($this->entityManager));

        return $df;
    }

    /**
     * @return Configuration
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getConfiguration(): Configuration
    {
        $config = new Configuration();
        $config->addMigrationsDirectory(
            $this->container->get('migration')['namespace'],
            $this->container->get('migration')['src'] ?? self::MIGRATION_PATH,
        );
        $config->setAllOrNothing(true);
        $config->setCheckDatabasePlatform(false);
        $config->setMetadataStorageConfiguration($this->configureStorage());

        return $config;
    }

    /**
     * @return array
     */
    protected function getDoctrineCommandsList(): array
    {
        return [
            new DumpSchemaCommand($this->dependencyFactory),
            new ExecuteCommand($this->dependencyFactory),
            new GenerateCommand($this->dependencyFactory),
            new LatestCommand($this->dependencyFactory),
            new ListCommand($this->dependencyFactory),
            new MigrateCommand($this->dependencyFactory),
            new RollupCommand($this->dependencyFactory),
            new StatusCommand($this->dependencyFactory),
            new SyncMetadataCommand($this->dependencyFactory),
            new VersionCommand($this->dependencyFactory),
            new DiffCommand($this->dependencyFactory)
        ];
    }
}

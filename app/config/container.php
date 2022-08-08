<?php

use DigitalStar\vk_api\vk_api;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Logging\Middleware;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use function DI\env;
use function DI\factory;

/** @var ContainerInterface $c */
return [
    'LOG_LEVEL' => env('LOG_LEVEL'),
    'DB_CONNECT_STRING' => env('DB_CONNECT_STRING'),
    'ENVIRONMENT' => env('ENVIRONMENT'),
    'VK_API_TOKEN' => env('VK_API_TOKEN'),
    'VK_API_VERSION' => env('VK_API_VERSION'),
    'VK_API_CONFIRM_STRING' => env('VK_API_CONFIRM_STRING'),
    'DB_HOST' => env('DB_HOST'),
    'DB_PORT' => env('DB_PORT'),
    'DB_USER' => env('DB_USER'),
    'DB_PASSWORD' => env('DB_PASSWORD'),
    'DB_NAME' => env('DB_NAME'),

    'DbConnectionParams' => factory(fn ($c) => [
        'host' => $c->get('DB_HOST'),
        'port' => $c->get('DB_PORT'),
        'user' => $c->get('DB_USER'),
        'password' => $c->get('DB_PASSWORD'),
        'dbname' => $c->get('DB_NAME'),
        'driver' => 'pdo_pgsql',
        'charset' => 'UTF8',
    ]),

    'isDev' => factory(fn ($c) => $c->get('ENVIRONMENT') != 'prod'),

    StreamHandler::class => factory(fn ($c) =>
        new RotatingFileHandler(
            __DIR__ . '/../logs/app.log',
            5,
            $c->get('LOG_LEVEL')
        )
    ),

    LoggerInterface::class => factory(fn ($c) =>
        (new Logger('bot'))->pushHandler($c->get(StreamHandler::class))
    ),

    Configuration::class => factory(fn ($c) =>
         ORMSetup::createAnnotationMetadataConfiguration(['src/Entities'], $c->get('isDev'))
             ->setMiddlewares([new Middleware($c->get(LoggerInterface::class))])
    ),

    DriverManager::class => factory(fn ($c) => DriverManager::getConnection($c->get('DbConnectionParams'))),

    EntityManager::class => factory(fn ($c) =>
        EntityManager::create($c->get(DriverManager::class), $c->get(Configuration::class)
    )),

    vk_api::class => factory(function ($c) {
        $vk = vk_api::create($c->get('VK_API_TOKEN'), $c->get('VK_API_VERSION'))->setConfirm($c->get('VK_API_CONFIRM_STRING'));
        if ($c->get('isDev')) {
            $vk->debug();
        }
        return $vk;
    }),
];


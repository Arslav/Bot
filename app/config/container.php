<?php

use DigitalStar\vk_api\vk_api;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

return [
    'LOG_LEVEL' => DI\env('LOG_LEVEL'),
    'DB_CONNECT_STRING' => DI\env('DB_CONNECT_STRING'),
    'ENVIRONMENT' => DI\env('ENVIRONMENT'),
    'VK_API_TOKEN' => DI\env('VK_API_TOKEN'),
    'VK_API_VERSION' => DI\env('VK_API_VERSION'),
    'VK_API_CONFIRM_STRING' => DI\env('VK_API_CONFIRM_STRING'),
    'DB_HOST' => DI\env('DB_HOST'),
    'DB_PORT' => DI\env('DB_PORT'),
    'DB_USER' => DI\env('DB_USER'),
    'DB_PASSWORD' => DI\env('DB_PASSWORD'),
    'DB_NAME' => DI\env('DB_NAME'),

    'isDev' => DI\factory(function ($c) {
        return $c->get('ENVIRONMENT') == 'dev';
    }),

    StreamHandler::class => DI\factory(function (ContainerInterface $c) {
        return new StreamHandler('logs/app.log', $c->get('LOG_LEVEL'));
    }),

    Psr\Log\LoggerInterface::class => DI\factory(function (ContainerInterface $c) {
        $logger = new Logger('bot');
        $logger->pushHandler($c->get(StreamHandler::class));
        return $logger;
    }),

    Doctrine\ORM\Configuration::class => DI\factory(function (ContainerInterface $c) {
        $paths = ['src/Entities'];
        return ORMSetup::createAnnotationMetadataConfiguration($paths, $c->get('isDev'));
    }),

    DriverManager::class => DI\factory(function (ContainerInterface $c) {
        return DriverManager::getConnection([
            'host' => $c->get('DB_HOST'),
            'port' => $c->get('DB_PORT'),
            'user' => $c->get('DB_USER'),
            'password' => $c->get('DB_PASSWORD'),
            'dbname' => $c->get('DB_NAME'),
            'driver' => 'pdo_pgsql',
            'charset' => 'UTF8',
        ]);
    }),

    EntityManager::class => DI\factory(function (ContainerInterface $c) {
        return EntityManager::create(
            $c->get(DriverManager::class),
            $c->get(Doctrine\ORM\Configuration::class)
        );
    }),

    vk_api::class => DI\factory(function (ContainerInterface $c) {
        $vk = vk_api::create($c->get('VK_API_TOKEN'), $c->get('VK_API_VERSION'))->setConfirm($c->get('VK_API_CONFIRM_STRING'));
        if ($c->get('isDev')) {
            $vk->debug();
        }
        return $vk;
    }),
];


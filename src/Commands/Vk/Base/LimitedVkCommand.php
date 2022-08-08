<?php

namespace Arslav\Newbot\Commands\Vk\Base;

use Arslav\Newbot\Services\CommandStats;
use DI\Annotation\Inject;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class LimitedVkCommand extends VkCommand
{
    public int $limit = 10;

    public int $interval = 300;

    /**
     * @Inject
     */
    public CommandStats $service;

    /**
     * @return bool
     *
     * @throws ContainerExceptionInterface
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws NotFoundExceptionInterface
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function beforeAction(): bool
    {
        $this->service->saveUsage(static::class, $this->from_id, $this->chat_id);
        return $this->checkLimit();
    }

    /**
     * @return bool
     *
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    protected function checkLimit(): bool
    {
        $usages = $this->service->usagesByInterval(
            $this->interval,
            static::class,
            $this->from_id,
            $this->chat_id
        );
        return $usages < $this->limit;
    }
}
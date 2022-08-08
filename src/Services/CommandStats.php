<?php

namespace Arslav\Newbot\Services;

use Arslav\Newbot\App;
use Arslav\Newbot\Entities\CommandLog;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Query\Parameter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CommandStats
{
    /**
     * @param string $commandClass
     * @param int $from_id
     * @param int|null $chat_id
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function saveUsage(string $commandClass, int $from_id, int $chat_id = null)
    {
        $em = App::getEntityManager();
        $command_log = new CommandLog();
        $command_log->setCommand($commandClass);
        $command_log->setFromId($from_id);
        $command_log->setChatId($chat_id);
        $em->persist($command_log);
        $em->flush($command_log);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function usagesByInterval(int $interval, string $commandClass, int $from_id, int $chat_id = null): int
    {
        $repository = App::getEntityManager()->getRepository(CommandLog::class);

        $params = new ArrayCollection([
            new Parameter('start', Carbon::now()->timestamp - $interval),
            new Parameter('end', Carbon::now()->timestamp),
            new Parameter('from_id', $from_id),
            new Parameter('command', $commandClass),
        ]);

        $query = $repository->createQueryBuilder('l')
            ->select('count(l.id)')
            ->where('l.created_at BETWEEN :start AND :end')
            ->andWhere('l.command = :command')
            ->andWhere('l.from_id = :from_id')
            ->setParameters($params);

        if ($chat_id) {
            $params->add(new Parameter('chat_id', $chat_id));
            $query->andWhere('l.chat_id = :chat_id');
        } else {
            $query->andWhere($query->expr()->isNull('l.chat_id'));
        }

        return $query->getQuery()->getSingleScalarResult();
    }

}
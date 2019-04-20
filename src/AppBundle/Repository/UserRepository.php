<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User repository.
 *
 * @author Pierre Jolly <pie.jolly@gmail.com>
 */
class UserRepository extends EntityRepository
{
    /**
     *
     */
    public function get(
        ArrayCollection $users = null,
        ArrayCollection $projects = null,
        ArrayCollection $tags = null,
        \DateTime $fromDate = null,
        \DateTime $toDate = null
    ) {
        $qb = $this->_em->createQueryBuilder();

        $qb->select($qb->expr()->concat('user.firstName', $qb->expr()->concat($qb->expr()->literal(' '), 'user.lastName')) . 'AS label')
            ->addSelect('SUM(task.duration) AS value')
//            ->addSelect('user.color AS color')
            ->from($this->_entityName, 'user')
            ->leftJoin('user.tasks', 'task')
            ->leftJoin('task.tags', 'tag')
            ->groupBy('user');

        if ($fromDate && $toDate) {
            $qb->andWhere('task.date >= :from_date')
                ->andWhere('task.date <= :to_date')
                ->setParameter('from_date', $fromDate)
                ->setParameter('to_date', $toDate);
        }

        if ($users && !$users->isEmpty()) {
            $qb->andWhere('task.user IN (:users)')
                ->setParameter('users', $users);
        }

        if ($projects && !$projects->isEmpty()) {
            $qb->andWhere('task.project IN (:projects)')
                ->setParameter('projects', $projects);
        }

        if ($tags && !$tags->isEmpty()) {
            $qb->andWhere('tag IN (:tags)')
                ->setParameter('tags', $tags);
        }

        return $qb->getQuery()->execute();
    }
}
